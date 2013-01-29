<?php
namespace NwBaseTest\Db\ResultSet;

use NwBase\Db\ResultSet\ResultSetPairs;

class ResultSetPairsTest extends \PHPUnit_Framework_TestCase
{
    public function testResultSetPairsToArray()
    {
        $dataSource = array(
            array('foo' => 15, 'bar' => 'teste 01'),
            array('foo' => 26, 'bar' => 'teste 10'),
            array('foo' => 57, 'bar' => 'teste 09'),
            array('foo' => 30, 'bar' => 'teste 08'),
            array('foo' => 40, 'bar' => 'teste 07'),
        );
        $result = new ResultSetPairs('foo', 'bar');
        $result->initialize($dataSource);
        
        $arrayExperado = array(
            '15' => 'teste 01',
            '26' => 'teste 10',
            '57' => 'teste 09',
            '30' => 'teste 08',
            '40' => 'teste 07',
        );
        $this->assertEquals($arrayExperado, $result->toArray(), "Formação do array pair invalido");
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Coluna Key invalida, para montagem do pair
     */
    public function testResultSetPairsColumnKeyInvalid()
    {
        $dataSource = array(
            array('foo' => 15, 'bar' => 'teste 01'),
            array('foo' => 26, 'bar' => 'teste 10'),
            array('foo' => 57, 'bar' => 'teste 09'),
            array('foo' => 30, 'bar' => 'teste 08'),
            array('foo' => 40, 'bar' => 'teste 07'),
        );
        $result = new ResultSetPairs('teste', 'bar');
        $result->initialize($dataSource);
        $array = $result->toArray();
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Coluna Value invalida, para montagem do pair
     */
    public function testResultSetPairsColumnValueInvalid()
    {
        $dataSource = array(
                array('foo' => 15, 'bar' => 'teste 01'),
                array('foo' => 26, 'bar' => 'teste 10'),
                array('foo' => 57, 'bar' => 'teste 09'),
                array('foo' => 30, 'bar' => 'teste 08'),
                array('foo' => 40, 'bar' => 'teste 07'),
        );
        $result = new ResultSetPairs('foo', 'teste');
        $result->initialize($dataSource);
        $array = $result->toArray();
    }
}