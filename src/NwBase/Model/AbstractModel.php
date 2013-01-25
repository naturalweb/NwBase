<?php
namespace NwBase\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Metadata\Metadata;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use NwBase\Entity\InterfaceEntity;
use NwBase\Model\InterfaceModel;
use NwBase\Db\Sql\Select;
use NwBase\Db\Sql\Update;
use NwBase\Db\Sql\Delete;

abstract class AbstractModel implements InterfaceModel, ServiceLocatorAwareInterface
{
    protected $tableName = null;
    protected $schemaName = null;
    protected $columnPrimary = null;
    private $columns = null;
    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;
    
    /**
     * @var Adapter
     */
    protected $dbAdapter = null;
    
    /**
     * @var TableGateway
     */
    protected $tableGateway = null;

    /**
     * @var TableObject
     */
    protected $metadataTable = null;

    abstract protected function getEntityPrototype();
    
    public function __construct(Adapter $dbAdapter = null)
    {
        if ($dbAdapter != null) {
            $this->setAdapter($dbAdapter);
        }
        
        if (!$this->getTableName()) {
            throw new \LogicException("Table name not found");
        }
    }
    
    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /**
     * @return Adapter
     */
    public function getAdapter()
    {
        if ($this->dbAdapter == null && $this->getServiceLocator() != null) {
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        
        return $this->dbAdapter;
    }
    
    /**
     * @param Adapter $dbAdapter
     * 
     * @return $this
     */
    public function setAdapter(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        
        return $this;
    }
        
    /**
     * @return TableGateway
     */
    public function getTableGateway()
    {
        if ($this->tableGateway == null && $this->getAdapter() != null) {
            $prototype = $this->getEntityPrototype();
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype($prototype);
            $tableGateway = new TableGateway($this->getTableName(), $this->getAdapter(), null, $resultSetPrototype);
            $this->tableGateway  = $tableGateway;
        }
        
        return $this->tableGateway;
    }

    /**
     * @return TableObject
     */
    public function getMetadataTable()
    {
        if ($this->metadataTable == null && $this->getAdapter() != null) {
            $metadata = new Metadata($this->getAdapter());
            $this->metadataTable = $metadata->getTable($this->getTableName(), $this->getSchemaName());
        }
        
        return $this->metadataTable;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getSchemaName()
    {
        return $this->schemaName;
    }

    /**
     * @return array
     */
    public function getColumnPrimary()
    {
        if (!$this->columnPrimary) {

            $columnPrimary = null;
            $listConstraints = $this->getMetadataTable()->getConstraints();
            if (is_array($listConstraints)) {
                foreach ($listConstraints as $constraint) {
                    if ($constraint->isPrimaryKey()) {
                        $columnPrimary = $constraint->getColumns();
                        break;
                    }
                }

                if ( is_array($columnPrimary) && count($columnPrimary)) {
                    $this->columnPrimary = $columnPrimary;
                }
            }
        }

        if (!$this->columnPrimary) {
            throw new \LogicException("Coluna primary não definida");
        }

        return (array) $this->columnPrimary;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        if (!$this->columns) {
            $this->columns = $this->getMetadataTable()->getColumns();
        }

        return $this->columns;
    }
    
    /**
     * @return array
     */
    public function getColumnsNames()
    {
        $cols = $this->getColumns();
        
        $extractNames = function ($val) {
            return $val->getName();
        };
        
        $cols = array_map($extractNames, $cols);
        
        return $cols;
    }

    protected function _whereFromPrimaryKeys($pkey, $not = false)
    {
        $where = array();
        $sinal = $not ? " != ?" : " = ?";

        $_columnPrimary = $this->getColumnPrimary();

        foreach ($_columnPrimary as $key) {
            if (is_array($pkey)) {
                $valKey = isset($pkey[$key]) ? $pkey[$key] : null;
                $where[$key. $sinal] = $valKey;
            } else {
                $valKey = !empty($pkey) ? $pkey : null;
            }

            $where[$key. $sinal] = $valKey;
        }

        return $where;
    }

    /**
     * @param $where
     *
     * @return Select
     */
    public function getSelect($where = null, $order = null, $limit = null, $offset = null)
    {
        $tableName = $this->getTableName();
        $select = new Select($tableName);

        // Filtros
        $select->where($where);

        // ORDENACAO DO SELECT
        if ( !empty($order) ) {
            $select->order($order);
        }

        // Limit
        $limit = (int) $limit;
        if ($limit) {
            $select->limit($limit);

            // Offset
            $offset = (int) $offset;
            if ($offset) {
                $select->offset($offset);
            }
        }

        return $select;
    }

    /**
     *
     * @return ResultSet
     */
    public function fetchAll(array $where = null)
    {
        $select = $this->getSelect($where);
        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    /**
     * @param mixed $where
     *
     * @return InterfaceEntity
     */
    public function fetchRow(array $where)
    {
        $select = $this->getSelect($where);
        $resultSet = $this->getTableGateway()->selectWith($select);
        $row = $resultSet->current();

        return $row;
    }

    /**
     * @param int|array $id
     *
     * @return InterfaceEntity
     */
    public function findById($id)
    {
        $where = $this->_whereFromPrimaryKeys($id);

        return $this->fetchRow($where);
    }
    
    /**
     * Metodo Magico para definição de metodos de find por campo
     * ex: findByUsername()
     * 
     * @param string $method Metodo
     * @param array $args Argumentos
     *  
     * @return InterfaceEntity
     */
    public function __call($method, array $args)
    {
        if (!preg_match('/^findBy(\w+)$/', $method, $matches)) {
            $msg = sprintf('Metodo "%s" inválido', $method);
            throw new \BadMethodCallException($msg);
        }
        $campo = preg_replace('/([A-Z])/', '_\\1', $matches[1]);
        $campo = trim($campo, '_');
        $campo = strtolower($campo);
        
        $cols = $this->getColumnsNames();
        if (!in_array($campo, $cols)) {
            throw new \LogicException('Coluna "'.$campo.'" para busca não existe na tabela');
        }
        
        if (!count($args)) {
            $msg = sprintf('Argumento obrigatorio para busca, no metodo "%s"', $method);
            throw new \InvalidArgumentException($msg);
        }
        
        $where = array(
            $campo => $args[0]
        );
        
        return $this->fetchRow($where);
    }
    
    /**
     * Faz a listagem trazendo os dados pareados, um array em chave e valor utilizando o metodo fetchPairs
     * Listando em pares key => value ex:(id, descricao)
     *
     * @param string $columnKey   Campo da Chave / Valor
     * @param string $columnValue Campo da Descricao / texto
     * @param array  $where    OPTIONAL Condição da busca
     * @param array  $order    OPTIONAL Campos default a serem inseridas
     *
     * @return Ambigous <multitype:, multitype:mixed >
     */
    public function fetchPairs($columnKey, $columnValue, array $where = null, $order = null, array $default = array())
    {
        $columns = array($columnKey, $columnValue);

        $select = $this->getSelect($where, $order);
        $select->reset(Select::COLUMNS);
        $select->columns($columns);

        $this->getTableGateway()->initialize();
        $sql       = $this->getTableGateway()->getSql();
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();

        $list = $default;
        foreach ($result as $row) {
            $list[$row[$columnKey]] = $row[$columnValue];
        }
        return $list;
    }

    /**
     * @param mixed $where
     *
     * @return int
     */
    public function count($where = null)
    {
        $select = $this->getSelect($where);
        $select->reset(Select::COLUMNS);
        $select->reset(Select::LIMIT);
        $select->reset(Select::OFFSET);

        $select->columns(array('c' => new Expression('COUNT(1)')));

        $this->getTableGateway()->initialize();
        $sql       = $this->getTableGateway()->getSql();
        $statement = $sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        $row       = $result->current();

        return (int) $row['c'];
    }

    /**
     * Verifica se ja existe o valor no campo informado
     *
     * @param string     $column     O Campo para verificar
     * @param string     $value     O Valor para verificar
     * @param int|string $value_primary O Valor da(s) Coluna(s) Primaria
     *
     * @return boolean
     */
    public function isUnique($column, $value, $value_primary = null)
    {
        $cols = $this->getColumnsNames();
        
        if (!in_array($column, $cols)) {
            throw new \LogicException('Coluna "'.$column.'" não existe na tabela');
        }

        if (is_null($value)) {
            return true;
        }

        $where = array();
        $where[$column] = $value;

        if ($value_primary) {
            $where += $this->_whereFromPrimaryKeys($value_primary, true);
        }

        return $this->count($where) ? false : true;
    }

    /**
     *
     * @param arrayInterfaceEntity $set
     */
    public function insert(InterfaceEntity $entity)
    {
        try {
            
            $values = $entity->getArrayCopy();

            $entity->preInsert($this);

            $return = $this->getTableGateway()->insert($values);

            $entity->postInsert($this);

            return $return;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param unknown_type $set
     * @param unknown_type $where
     */
    public function update($set, $where = null)
    {
        if ($set instanceof InterfaceEntity) {
            return $this->updateEntity($set);
        }

        $update = new Update($this->tableName);
        $update->set($set);
        if ($where !== null) {
            $update->where($where);
        }

        return $this->getTableGateway()->updateWith($update);
    }

    public function updateEntity(InterfaceEntity $entity)
    {
        try {
            
            $values = $entity->getArrayCopy();
            $where = $this->_whereFromPrimaryKeys($values);

            if (!count($where) || array_search(null, $where)!==false) {
                throw new \Exception("Valor da chave primaria não definida");
            }

            $entity->preUpdate($this);

            $return = $this->getTableGateway()->update($values, $where);

            $entity->postUpdate($this);

            return $return;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param array|InterfaceEntity $where
     */
    public function delete($where)
    {
        if ($where instanceof InterfaceEntity) {
            return $this->deleteEntity($where);
        }

        $delete = new Delete($this->tableName);
        if ($where !== null) {
            $delete->where($where);
        }

        return $this->getTableGateway()->deleteWith($delete);
    }

    public function deleteEntity(InterfaceEntity $entity)
    {
        try {
            
            $campos = $entity->getArrayCopy();
            $where = $this->_whereFromPrimaryKeys($campos);

            if (!count($where) || array_search(null, $where)!==false) {
                throw new \Exception("Valor da chave primaria não definida");
            }

            $entity->preDelete($this);

            $return = $this->getTableGateway()->delete($where);

            $entity->postDelete($this);

            unset($entity);

            return $return;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get last insert value
     *
     * @return integer
     */
    public function getLastInsertValue()
    {
        return $this->getTableGateway()->getLastInsertValue();
    }
}
