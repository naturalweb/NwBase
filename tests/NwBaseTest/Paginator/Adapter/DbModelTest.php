<?php

namespace NwBaseTest\Paginator\Adapter;

require_once __DIR__ . '/../../Model/_files/FooBarModel.php';

use NwBaseTest\Model\FooBarModel;
use NwBase\Paginator\Adapter\DbModel as ModelAdapter;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $adapter;
    protected $model;
    protected $tableNameTest = 'table_test';
    
    protected function setUp()
    {
        $driver = "pdo_sqlite";
        $charset  = 'utf8';
        $dsn = sprintf("sqlite::memory:");
        $db = array(
            'driver'         => $driver,
            'dsn'            => $dsn,
            'charset'        => $charset,
        );
        $this->adapter = new Adapter($db);
        $sql = '
        CREATE TABLE IF NOT EXISTS '.$this->tableNameTest.' (
            foo INTEGER PRIMARY KEY,
            bar TEXT
        );';
        $this->adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (1, 'valor 1')", Adapter::QUERY_MODE_EXECUTE); 
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (2, 'valor 2')", Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (3, 'valor 3')", Adapter::QUERY_MODE_EXECUTE);
        $this->adapter->query("INSERT INTO {$this->tableNameTest} VALUES (4, 'valor 4')", Adapter::QUERY_MODE_EXECUTE);
        
        $this->model = new FooBarModel($this->adapter);
    }
    
    protected function tearDown()
    {
        $this->adapter->query('DROP TABLE ' . $this->tableNameTest, Adapter::QUERY_MODE_EXECUTE);
    }
    
    public function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($class = 'NwBase\Paginator\Adapter\DbModel'),
            "Classe Paginator Adapter Invalida não existe: " . $class
        );
    }
    
    public function testConstrutorPaginatorAdapterDbTable()
    {
        $actualAdapter = new ModelAdapter($this->model);
        $this->assertInstanceOf('Zend\Paginator\Adapter\DbSelect', $actualAdapter);
        
        $this->assertEquals(4, $actualAdapter->count(), "Contagem de registro errada");
        
        $itens = $actualAdapter->getItems(1, 2);
        $this->assertInstanceOf('Zend\Db\ResultSet\ResultSet', $itens, 'Tipo do retorno do get itens invalido');
        $this->assertEquals(2, $itens->count(), "Itens retornadas errrado");
        
        $this->assertInstanceOf('NwBase\Entity\AbstractEntity', $itens->getArrayObjectPrototype(), "Prototype invalido");
        
        $expected = array(
            array(
                'foo' => "2",
                'bar' => "valor 2",
                'poliforlismo' => null,
            ),
            array(
                'foo' => "3",
                'bar' => "valor 3",
                'poliforlismo' => null,
            )
        );
        $this->assertEquals($expected, $itens->toArray(), "Itens retornados invalidos de retorno");
    }
}
