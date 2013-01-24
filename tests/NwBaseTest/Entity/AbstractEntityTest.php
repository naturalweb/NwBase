<?php

namespace NwBaseTest\Entity;

require_once __DIR__ . '/../Tests/FooBarModel.php';

use NwBase\Entity\AbstractEntity;
use NwBase\DateTime\DateTime as NwDateTime;
use NwBase\DateTime\Date as NwDate;
use NwBaseTest\Tests\FooBarEntity;

class AbstractEntityTest extends \PHPUnit_Framework_TestCase
{
    public function assertPreConditions()
    {
        $this->assertTrue(
                class_exists($class = 'NwBase\Entity\AbstractEntity'),
                "Classe Abstract Entity não existe: " . $class
        );
    }
    
    public function testConstrutorAbstractEntityArrayCopy()
    {
        $valores = array(
            'foo' => 'teste', 
            'bar'=>'blabla', 
            'poliforlismo' => new NwDate('09/24/2012'),
        );
        $myTest = new FooBarEntity($valores);
        
        $expected = $valores;
        $expected['poliforlismo'] = '2012-09-24';
        
        $arrayCopy = $myTest->getArrayCopy();
        $this->assertEquals($expected, $arrayCopy, "Array Copy, Valores de retorno Invalido");
        $this->assertSame($arrayCopy, $myTest->toArray(), "To Array, Valores de retorno invalido, deve ser como array copy");
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testExchangeArrayComArrayObject()
    {
        $valores = array('foo'=>'teste', 'bar'=>'blabla', 'poliforlismo' => new \DateTime('09/24/2012'),);
        $obj = new \ArrayObject($valores);
        
        $myTest = new FooBarEntity();
        $return = $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
        $this->assertEquals($myTest, $return, "Não retorno sua propria instancia");
        
        $arrayCopy = $myTest->getArrayCopy();
        $expected = $valores;
        $expected['poliforlismo'] = '2012-09-24 00:00:00';
        $this->assertEquals($expected, $arrayCopy, "Array Copy, Valores de retorno Invalido");
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testExchangeArrayComObjetosImplementadoInterfaceEntity()
    {
        $valores = array(
            'foo' => 'teste', 
            'bar' => 'blabla',
        );
        $obj = new FooBarEntity($valores);
        
        $myTest = new FooBarEntity();
        $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testExchangeArrayComObjetosGenericos()
    {
        $valores = array('foo'=>'teste', 'bar'=>'blabla');
        $obj = new \stdClass();
        $obj->foo = $valores['foo'];
        $obj->bar = $valores['bar'];
    
        $myTest = new FooBarEntity();
        $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testRetornoMetodoToStringEmptyAbstract()
    {
        $mockTest = $this->getMockForAbstractClass('NwBase\Entity\AbstractEntity', array('toString'));
        
        $string = (string) $mockTest->toString();
        $this->assertEmpty($string);
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testMetodoToStringExtendido()
    {
        $foo = 'valor1';
        $bar = 'valor2';
        $myTest = new FooBarEntity();
        $myTest->setFoo($foo)->setBar($bar);
        
        $strExpected = "FOO: {$foo}, BAR: {$bar}";
        
        $string = (string) $myTest;
        $this->assertEquals($strExpected, $string, "String de retorno invalida");
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Set direto da propriedade não permitido, Utilize o metodos "set"
     */
    public function testMetodoMagicoSetThrowException()
    {
        $myTest = new FooBarEntity();
        $myTest->__set('foo', 'novo valor');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Propriedade "novacoluna" inválida
     */
    public function testMetodoMagicoGetNaoExisteThrowException()
    {
        $myTest = new FooBarEntity();
        $myTest->__get('novacoluna', 'novo valor');
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testMetodoMagicoCallSetterValores()
    {
        $foo = 'valor';
    
        $myTest = new FooBarEntity();
        
        $return = $myTest->__call('setFoo', array($foo));
        
        $this->assertAttributeEquals($foo, 'foo', $myTest, "Não setou o valor com o Metodo Call");
        $this->assertEquals($myTest, $return, "Não retorno sua propria instancia");
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Propriedade "nova_coluna" inválida
     */
    public function testMetodoMagicoCallThrowException()
    {
        $myTest = new FooBarEntity();
        $myTest->__call('setNovaColuna', 'novo valor');
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testMetodoMagicoCallGetterValor()
    {
        $bar = 'valor';
    
        $myTest = new FooBarEntity();
        $myTest->setBar($bar);
        
        $return = $myTest->__call('getBar', array($bar));
        
        $this->assertEquals($bar, $return, "Não retorno o valor correto");
    }
    
    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage Metodo "naoExiste" inválido
     */
    public function testMetodoMagicoCallBadMethodCallThrowException()
    {
        $myTest = new FooBarEntity();
        $myTest->__call('naoExiste', array('novo valor'));
    }
    
    /**
     * @depends testConstrutorAbstractEntityArrayCopy
     */
    public function testPoliformismoMetodoGet()
    {
        $poliforlismo = 'valor poli';
    
        $myTest = new FooBarEntity();
        $myTest->setPoliforlismo($poliforlismo);
        
        $return = $myTest->__get('poliforlismo');
        $this->assertEquals($poliforlismo, $return, "Não retornou o valor correto");
    }
    
    public function testValueDateTimeWithObjDateTime()
    {
        $dateOrig = new NwDateTime('2012-12-21 10:55:33');
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATETIME, $dateOrig);
        $this->assertSame($dateOrig, $valueReturn);
        
        // DateTime from PHP
        $dateOrig = new \DateTime('2012-12-21 01:03:05');
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATE, $dateOrig);
        $this->assertInstanceOf('NwBase\DateTime\Date', $valueReturn);
        $this->assertEquals('2012-12-21', $valueReturn);
        
        $dateOrig = new \DateTime('2012-12-21 01:03:05');
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::TIME, $dateOrig);
        $this->assertInstanceOf('NwBase\DateTime\Time', $valueReturn);
        $this->assertEquals('01:03:05', $valueReturn);
    }
    
    public function testValueDateTimeWithValues()
    {
        $valueOrig = '1980-01-31 20:05:17';
        
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATETIME, $valueOrig);
        $this->assertInstanceOf('NwBase\DateTime\DateTime', $valueReturn);
        $this->assertEquals('1980-01-31 20:05:17', $valueReturn);
        
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATE, $valueOrig);
        $this->assertInstanceOf('NwBase\DateTime\Date', $valueReturn);
        $this->assertEquals('1980-01-31', $valueReturn);
        
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::TIME, $valueOrig);
        $this->assertInstanceOf('NwBase\DateTime\Time', $valueReturn);
        $this->assertEquals('20:05:17', $valueReturn);
    }
    
    public function testValueDateTimeVaueEmpty()
    {
        $valueOrig = '';
        
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATE, $valueOrig);
        $this->assertNull($valueReturn);
    }
    
    public function testValueDateTimeFormatInvalid()
    {
        $valueOrig = '1980-01-31 20:05:17';
    
        $valueReturn = AbstractEntity::valueDateTime('d/m/Y', $valueOrig);
        $this->assertNull($valueReturn);
    }
    
    public function testValueDateTimeValueInvalid()
    {
        $valueOrig = '21/02/2012';
    
        $valueReturn = AbstractEntity::valueDateTime(NwDateTime::DATE, $valueOrig);
        $this->assertNull($valueReturn);
    }
}
