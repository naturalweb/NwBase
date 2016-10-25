<?php

namespace NwBaseTest\Model;

// Somente para os Testes
use NwBase\Model\AbstractNonEntityModel;

require_once __DIR__ . '/_files/FooBarNonEntityModel.php';

use NwBaseTest\Model\FooBarNonEntityModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\ServiceManager\ServiceManager;

class AbstractNonEntityModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;
    protected $model;
    protected $tableNameTest = 'table_test';

    public function setUp()
    {
        $driver  = "pdo_sqlite";
        $charset = 'utf8';
        $dsn     = sprintf("sqlite::memory:");
        $db      = array(
            'driver'         => $driver,
            'dsn'            => $dsn,
            'charset'        => $charset,
        );
        $this->adapter = new Adapter($db);
        $this->model   = new FooBarNonEntityModel($this->adapter);
    }

    /**
     * Este método será chamado para os últimos 4 testes desta classe, que testam prepareAndExecuteStatement para
     * select, insert, update e delete
     *
     * @return void
     */
    public function setUpForLastFourTests()
    {
        $sql = '
        CREATE TABLE IF NOT EXISTS '.$this->tableNameTest.' (
            foo INTEGER PRIMARY KEY,
            bar VARCHAR(20) NOT NULL
        );';
        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (1, 'valor 1')", Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (2, 'valor 2')", Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (3, 'valor 3')", Adapter::QUERY_MODE_EXECUTE);
    }

    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($class = 'NwBase\Model\AbstractNonEntityModel'),
            "Classe Abstract Model não existe: " . $class
        );
    }

    public function testServiceLocatorAwareInterface()
    {
        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Zend\Db\Adapter\Adapter', $this->adapter);

        $model = new FooBarNonEntityModel();

        $this->assertAttributeEmpty("serviceLocator", $model);

        $model->setServiceLocator($serviceLocator);

        // Service
        $this->assertEquals($serviceLocator, $model->getServiceLocator());
        $this->assertAttributeEquals($serviceLocator, 'serviceLocator', $model);
    }

    public function testConstructWithoutAdapterWithoutServiceLocator()
    {
        $model   = new FooBarNonEntityModel();
        $adapter = $model->getAdapter();

        $this->assertSame($adapter, null, "Adapter deveria ser null porque não tem ServiceLocator");
    }

    /**
     * Garante que Model sem adapter acaba setando adapter após chamar getAdapter()
     *
     * @return void
     */
    public function testConstructWithoutAdapterWithServiceLocator()
    {
        $model   = new FooBarNonEntityModel();

        $serviceLocator = new ServiceManager();
        $serviceLocator->setService('Zend\Db\Adapter\Adapter', $this->adapter);

        $model->setServiceLocator($serviceLocator);

        $adapter = $model->getAdapter();

        $this->assertSame($this->adapter, $adapter, "Objeto deveria ser do mesmo tipo Adapter");
    }

    public function testConstructWithAdapter()
    {
        $this->assertSame($this->adapter, $this->model->getAdapter(), "Deveria retornar o Adapter");
    }

    public function testGetSchemaNameIsNull()
    {
        $this->assertNull($this->model->getSchemaName());
    }


    public function testBeginTransaction()
    {
        $mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $mockConnection->expects($this->once())->method('beginTransaction');

        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockDriver->expects($this->once())->method('getConnection')->will($this->returnValue($mockConnection));

        $mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', array(), array($mockDriver));
        $mockAdapter->expects($this->once())->method('getDriver')->will($this->returnValue($mockDriver));

        $model = new FooBarNonEntityModel($mockAdapter);
        $model->beginTransaction();
    }

    public function testCommit()
    {
        $mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $mockConnection->expects($this->once())->method('commit');

        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockDriver->expects($this->once())->method('getConnection')->will($this->returnValue($mockConnection));

        $mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', array(), array($mockDriver));
        $mockAdapter->expects($this->once())->method('getDriver')->will($this->returnValue($mockDriver));

        $model = new FooBarNonEntityModel($mockAdapter);
        $model->commit();
    }

    public function testRollback()
    {
        $mockConnection = $this->getMock('Zend\Db\Adapter\Driver\ConnectionInterface');
        $mockConnection->expects($this->once())->method('rollback');

        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockDriver->expects($this->once())->method('getConnection')->will($this->returnValue($mockConnection));

        $mockAdapter = $this->getMock('Zend\Db\Adapter\Adapter', array(), array($mockDriver));
        $mockAdapter->expects($this->once())->method('getDriver')->will($this->returnValue($mockDriver));

        $model = new FooBarNonEntityModel($mockAdapter);
        $model->rollback();
    }

    public function testGetSql()
    {
        $sql         = $this->model->getSql();
        $expectedSql = new Sql($this->adapter);
        $this->assertInstanceOf('Zend\Db\Sql\Sql', $sql);
        $this->assertEquals($expectedSql, $sql);
    }

    public function testPrepareAndExecuteStatementSelect()
    {
        $this->setUpForLastFourTests();

        $select = $this->model->getSql()->select($this->tableNameTest);
        $return = $this->model->prepareAndExecuteStatement($select);

        $this->assertInstanceOf('Zend\Db\Adapter\Driver\Pdo\Result', $return);
        $this->assertEquals($return->count(), 3); // Numero de registros inseridos em $this->setUpForLastFourTests();
    }

    public function testPrepareAndExecuteStatementInsert()
    {
        $this->setUpForLastFourTests();

        // Verifica existência dos 3 registros padrão
        $select = $this->model->getSql()->select($this->tableNameTest);
        $return = $this->model->prepareAndExecuteStatement($select);
        $this->assertEquals($return->count(), 3); // Numero de registros inseridos em $this->setUpForLastFourTests();


        $insert = $this->model->getSql()->insert($this->tableNameTest);
        $insert->columns(array('bar'));
        $insert->values(array('bar' => 'easter egg'));

        $this->model->prepareAndExecuteStatement($insert);

        // Verifica se inserção ocorreu com sucesso
        $return2 = $this->model->prepareAndExecuteStatement($select);
        $this->assertEquals($return2->count(), 4);
    }

    public function testPrepareAndExecuteStatementUpdate()
    {
        $this->setUpForLastFourTests();

        $oldValue = 'valor 1'; // especificado em $this->setUpForLastFourTests();
        $newValue = 'editei'; // Novo valor após update

        // Verifica existência dos 3 registros padrão
        $select = $this->model->getSql()->select($this->tableNameTest);
        $select->order(array('foo'));

        $return = $this->model->prepareAndExecuteStatement($select);

        $this->assertEquals($return->count(), 3); // Numero de registros inseridos em $this->setUpForLastFourTests();

        // Verifica o valor padrão da primeira linha
        foreach ($return as $row) {
            $this->assertEquals($row['bar'], $oldValue);
            break;
        }

        // Atualiza
        $update   = $this->model->getSql()->update($this->tableNameTest);
        $update->set(array('bar' => $newValue));
        $update->where(array('foo' => 1));

        $this->model->prepareAndExecuteStatement($update);

        // Verifica se update ocorreu com sucesso na primeira linha
        $return2 = $this->model->prepareAndExecuteStatement($select);

        foreach ($return2 as $row) {
            $this->assertEquals($row['bar'], $newValue);
            break;
        }

        $this->assertEquals($return2->count(), 3);
    }

    public function testPrepareAndExecuteStatementDelete()
    {
        $this->setUpForLastFourTests();

        // Verifica existência dos 3 registros padrão
        $select = $this->model->getSql()->select($this->tableNameTest);
        $return = $this->model->prepareAndExecuteStatement($select);
        $this->assertEquals($return->count(), 3); // Numero de registros inseridos em $this->setUpForLastFourTests();

        // Exclui registros foo > 1
        $delete = $this->model->getSql()->delete($this->tableNameTest);
        $delete->where(array('foo > ?' => 1));
        $this->model->prepareAndExecuteStatement($delete);

        // Verifica se inserção ocorreu com sucesso
        $return2 = $this->model->prepareAndExecuteStatement($select);
        $this->assertEquals($return2->count(), 1);
    }
}
