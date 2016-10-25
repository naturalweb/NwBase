<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\SqlInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Cache\Storage\StorageInterface as CacheStorageInterface;
use NwBase\Entity\InterfaceEntity;
use NwBase\Model\InterfaceNonEntityModel;
use NwBase\Db\ResultSet\ResultSetPairs;

/**
 * Abstração para tratar com o database *sem considerar uma tabela específica do banco de dados*
 *
 * @category NwBase
 * @package  NwBase\Model
 * @author   Edson Horácio Junior <edson.junior@becinteligencia.com.br>
 * @abstract
 */
abstract class AbstractNonEntityModel implements InterfaceNonEntityModel, ServiceLocatorAwareInterface
{
    protected static $_instance;
    protected static $_staticServiceLocator;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var string
     */
    protected $_schemaName = null;

    /**
     * @var Adapter
     */
    protected $_dbAdapter = null;

    /**
     * @var \Zend\Db\Sql\Sql
     */
    protected $_sql = null;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        self::$_staticServiceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

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
     * Retorna nome do Schema do database
     *
     * @return string
     */
    public function getSchemaName()
    {
        return $this->_schemaName;
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

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (preg_match('/^st[A-Z]/', $method)) {
            $method = strtolower(substr($method, 2, 1)) . substr($method, 3);
        }

        if (self::$_instance) {
            $instance = self::$_instance;
        } else {
            $instance = new static();
            $instance->setServiceLocator(self::$_staticServiceLocator);

            self::$_instance = $instance;
        }

        return call_user_func_array(array($instance, $method), $args);
    }

    /**
     * Atalho para criar objeto Sql com Adapter
     *
     * @return Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        if (!$this->_sql) {
            $this->_sql = new Sql($this->getAdapter());
        }

        return $this->_sql;
    }

    /**
     * Atalho para criar statement, prepara-lo e executa-lo
     *
     * @param  SqlInterface $objSql Objeto do tipo select, insert, update ou delete
     *
     * @return Zend\Db\Adapter\Driver\Pdo\Result
     */
    public function prepareAndExecuteStatement(SqlInterface $objSql)
    {
        $statement = $this->getSql()->prepareStatementForSqlObject($objSql);
        $statement->prepare();
        return $statement->execute();
    }
}
