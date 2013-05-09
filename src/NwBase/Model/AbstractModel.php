<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\TableIdentifier;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\Storage\StorageInterface as CacheStorageInterface;
use NwBase\Entity\InterfaceEntity;
use NwBase\Model\InterfaceModel;
use NwBase\Db\ResultSet\ResultSetPairs;

/**
 * Abstração para tratar com o database para uma tabela do banco de dados
 * 
 * @category NwBase
 * @package  NwBase\Model
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractModel implements InterfaceModel, ServiceLocatorAwareInterface
{
    /** 
     * @var string
     */
    protected $_tableName = null;
    
    /** 
     * @var string
     */
    protected $_schemaName = null;
    
    /** 
     * @var array
     */
    protected $_columnPrimary = null;
    
    /** 
     * @var array
     */
    private $_columns = null;
    
    /** 
     * @var ServiceLocatorInterface
     */
    protected $_serviceLocator;
    
    /**
     * @var Adapter
     */
    protected $_dbAdapter = null;
    
    /**
     * @var TableGateway
     */
    protected $_tableGateway = null;

    /**
     * @var TableObject Metadata da Tabela
     */
    protected $_metadataTable = null;
    
    /**
     * Cache do Metadata
     *
     * @var CacheStorageInterface
     */
    protected $_metadataCache = null;
    
    /**
     * Cache padrão para as informações fornecidas pelo método AbstractModel
     *
     * @var CacheStorageInterface
     */
    protected static $_defaultCache = null;
    
    /**
     * Retorna o prototype da Entity
     * 
     * @return InterfaceEntity
     */
    abstract protected function getEntityPrototype();
    
    /**
     * Construtor
     * 
     * @param Adapter $dbAdapter Adapter Database
     * 
     * @throws \LogicException
     */
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
     * @param ServiceLocatorInterface $serviceLocator Object Service
     * 
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->_serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->_serviceLocator;
    }
    
    /**
     * Retorna o Adapter do database
     * 
     * @return Adapter
     */
    public function getAdapter()
    {
        if ($this->_dbAdapter == null && $this->getServiceLocator() != null) {
            $this->_dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        
        return $this->_dbAdapter;
    }
    
    /**
     * Seta o Adapter
     * 
     * @param Adapter $dbAdapter Adatapter do database
     * 
     * @return $this
     */
    public function setAdapter(Adapter $dbAdapter)
    {
        $this->_dbAdapter = $dbAdapter;
        return $this;
    }

    /**
     * Sets the default cache for information returned by AbstractModel.
     *
     * @param CacheStorageInterface $cache Object Cache Default
     * 
     * @return void
     */
    public static function setDefaultCache(CacheStorageInterface $cache)
    {
        self::$_defaultCache = $cache;
    }
    
    /**
     * Gets the default cache for information returned by AbstractModel.
     *
     * @return CacheStorageInterface
     */
    public static function getDefaultCache()
    {
        return self::$_defaultCache;
    }
    
    /**
     * Cache do metadata
     * 
     * @param CacheStorageInterface $metadataCache Cache do Metadata
     * 
     * @return void
     */
    public function setMetadataCache(CacheStorageInterface $metadataCache)
    {
        $this->_metadataCache = $metadataCache;
    }
    
    /**
     * Cache do metadata
     * 
     * @return CacheStorageInterface
     */
    public function getMetadataCache()
    {
       if (!$this->_metadataCache) {
           $this->_metadataCache = self::getDefaultCache();
       }
       
       return $this->_metadataCache;
    }
    
    /**
     * Objeto TableGateway
     * 
     * @return TableGateway
     */
    public function getTableGateway()
    {
        if ($this->_tableGateway == null && $this->getAdapter() != null) {
            $prototype = $this->getEntityPrototype();
            
            $serviceLocator = $this->getServiceLocator();
            if ($prototype instanceof ServiceLocatorAwareInterface && $serviceLocator != null) {
                $prototype->setServiceLocator($serviceLocator);
            }
            
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype($prototype);
            $tableGateway = new TableGateway($this->getTableIdentifier(), $this->getAdapter(), null, $resultSetPrototype);
            $this->_tableGateway  = $tableGateway;
        }
        
        return $this->_tableGateway;
    }
    
    /**
     * Set Objeto TableGateway Manualmente
     *
     * @param TableGateway $tableGateway Obj TableGateway
     * 
     * @return InterfaceModel
     */
    public function setTableGateway(TableGateway $tableGateway)
    {
        if ($this->getTableIdentifier() != $tableGateway->getTable()) {
            throw new \RuntimeException('A Identificação da tabela no objeto deve corresponder');
        }
        
        $this->_tableGateway = $tableGateway;
        return $this;
    }
    
    /**
     * Retorna o object TableObject que representa o metadata da tabela
     * 
     * @return TableObject
     */
    public function getMetadataTable()
    {
        if (!$this->_metadataTable && $this->getAdapter() != null) {
            $metadataCache = $this->getMetadataCache();
            
            // dsn;schema.table
            $parameters = $this->getAdapter()->getDriver()->getConnection()->getConnectionParameters();
            $key = sprintf('%s;%s.%s', $parameters['dsn'], $this->getSchemaName(), $this->getTableName());
            $key = md5($key);
            
            if (!$metadataCache || ($metadataCache && !$this->_metadataTable = $metadataCache->getItem($key, $success))) {
                $metadata = new Metadata($this->getAdapter());
                $this->_metadataTable = $metadata->getTable($this->getTableName(), $this->getSchemaName());
                if ($metadataCache != null && $this->_metadataTable) {
                    $metadataCache->setItem($key, $this->_metadataTable);
                }
            }
        }
        
        return $this->_metadataTable;
    }
    
    /**
     * Retorna o object TableIdentifier
     *
     * @return TableIdentifier
     */
    public function getTableIdentifier()
    {
        return new TableIdentifier($this->_tableName, $this->_schemaName);
    }
    
    /**
     * Retorna nome do Tabela no database
     * 
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * Retorna nome do Schema do database
     * 
     * @return string
     */
    public function getSchemaName()
    {
        return $this->_schemaName;
    }

    /**
     * Retorna array com o name das colunas primarias
     * 
     * @return array
     */
    public function getColumnPrimary()
    {
        if (!$this->_columnPrimary) {

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
                    $this->_columnPrimary = $columnPrimary;
                }
            }
        }

        if (!$this->_columnPrimary) {
            throw new \LogicException("Coluna primary não definida");
        }

        return (array) $this->_columnPrimary;
    }

    /**
     * Array com os objetos de Column
     * 
     * @return array
     */
    public function getColumns()
    {
        if (!$this->_columns) {
            $this->_columns = $this->getMetadataTable()->getColumns();
        }

        return $this->_columns;
    }
    
    /**
     * Nome das Colunas da tabela do database
     * 
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
    
    /**
     * Cria um array com as condições para busca um registro atraves da chave primary
     *  
     * @param int|string|array $pkey Valor da Chave
     * @param boolean          $not  Logica negativa ou não
     * 
     * @return array
     */
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
     * Cria o objeto Select baseado no argumentos
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     *
     * @return Select
     */
    public function getSelect($where = null, $order = null, $limit = null, $offset = null)
    {
        $select = new Select($this->getTableIdentifier());

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
     * Retorna o resultado da busca no objeto ResultSet
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     * @param string|array                $order Ordenação
     * 
     * @return ResultSet
     */
    public function fetchAll($where = null, $order = null)
    {
        $select    = $this->getSelect($where, $order);
        $resultSet = $this->getTableGateway()->selectWith($select);

        return $resultSet;
    }

    /**
     * Busca o primeiro registro da condição da busca
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
     *
     * @return InterfaceEntity
     */
    public function fetchRow($where)
    {
        $limit = 1;
        $order = null;
        $select = $this->getSelect($where, $order, $limit);
        
        $resultSet = $this->getTableGateway()->selectWith($select);
        $row = $resultSet->current();

        return $row;
    }

    /**
     * Faz a busca pela coluna(s) de chave primary
     * 
     * @param int|array $id valor do ID
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
     * @param string $method Name do Metodo
     * @param array  $args   Argumentos recebidos
     *  
     * @return mixed
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
     * @param string                      $columnKey     Campo da Chave / Valor
     * @param string                      $columnValue   Campo da Descricao / texto
     * @param Where|\Closure|string|array $where         OPTIONAL Condição da busca
     * @param array                       $order         OPTIONAL Campos default a serem inseridas
     * @param array                       $valuesDefault OPTIONAL Inseriri no inicio como default
     *
     * @return ResultSetPairs
     */
    public function fetchPairs($columnKey, $columnValue, $where = null, $order = null, array $valuesDefault = array())
    {
        $columns = array($columnKey, $columnValue);
        // Monta Select
        $select = $this->getSelect($where, $order);
        $select->reset(Select::COLUMNS);
        $select->columns($columns);
        
        // Executa Busca Database
        $this->getTableGateway()->initialize();
        $sql        = $this->getTableGateway()->getSql();
        $statement  = $sql->prepareStatementForSqlObject($select);
        $dataSource = $statement->execute();
        
        // Monta o ResultSet
        $resultSetPairs = new ResultSetPairs($columnKey, $columnValue, $valuesDefault);
        $resultSetPairs->initialize($dataSource);
        return $resultSetPairs;
    }

    /**
     * Conta a quantidade de registro pela condição
     * 
     * @param Where|\Closure|string|array $where Condição da Busca
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
     * @param string     $column        O Campo para verificar
     * @param string     $value         O Valor para verificar
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
     * Insere um registro, retornar o numero de registros afetados e busca a entity nova atraves do id
     * 
     * @param InterfaceEntity $entity Object do registro
     * 
     * @throws \Exception
     * @todo utilizar o Object Hydrator para inserção e edição do Entity
     * @return number
     */
    public function insert(InterfaceEntity $entity)
    {
        try {
            
            $entity->preInsert($this);
            
            $values = $entity->getArrayCopy();
            $values = array_filter($values, function($val){
                return !is_null($val);
            });
            
            $return = $this->getTableGateway()->insert($values);
            
            $entity->postInsert($this);
            
            // Seta o Id Primary caso exista
            $lastInsertId = $this->getLastInsertValue();
            $columnPrimary = $this->getColumnPrimary();
            if ($lastInsertId) {
                $entity->setProperty($columnPrimary[0], $lastInsertId);
            }
            
            return $return;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Edita o registro baseado na entity, e retornar o numero de registros afetados
     * 
     * @param InterfaceEntity $entity Object do registro
     * 
     * @throws \Exception
     * @todo utilizar o Object Hydrator para inserção e edição do Entity
     * @return number
     */
    public function update(InterfaceEntity $entity)
    {
        try {
            
            $values = $entity->getArrayCopy();
            $where = $this->_whereFromPrimaryKeys($values);
            if (!count($where) || array_search(null, $where)!==false) {
                throw new \Exception("Valor da chave primaria não definida");
            }
            
            $entity->preUpdate($this);
            
            $values = $entity->getArrayCopy();
            if ($entity->getStored()) {
                
                
                $columnPrimary = $this->getColumnPrimary();
                $modified = $entity->getModified();
                
                if (count(array_intersect_key($modified, array_flip($columnPrimary)))) {
                    $message = sprintf('Os campos "%s" não podem ser alterados por serem chave(s) primaria(s)', implode(', ', $columnPrimary));
                    throw new \Exception($message);
                }
                
                // Define os valores a serem salvos, baseados nos valores modificados
                $values = array_intersect_key($values, $modified);
            }
            
            $return = null;
            
            if (count($values)) {
                $return = $this->getTableGateway()->update($values, $where);
            }
            
            $entity->postUpdate($this);
            
            $entity->clearModified();
            
            return $return;
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Excluir o registro da entity e o objeto
     * 
     * @param InterfaceEntity $entity Object do registro
     * 
     * @throws \Exception
     * @return number
     */
    public function delete(InterfaceEntity $entity)
    {
        try {
            
            $values = $entity->getArrayCopy();
            $where = $this->_whereFromPrimaryKeys($values);
            
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
    
    /**
     * Begin transaction
     *
     * @return void
     */
    public function beginTransaction()
    {
        return $this->getAdapter()->getDriver()->getConnection()->beginTransaction();
    }
    
    /**
     * Commit
     *
     * @return void
     */
    public function commit()
    {
        return $this->getAdapter()->getDriver()->getConnection()->commit();
    }
    
    /**
     * Rollback
     *
     * @throws Exception\RuntimeException
     * @return Connection
     */
    public function rollback()
    {
        return $this->getAdapter()->getDriver()->getConnection()->rollback();
    }
}
