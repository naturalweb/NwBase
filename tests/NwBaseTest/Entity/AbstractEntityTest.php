<?php

namespace NwBaseTest\Entity;

require_once __DIR__ . '/_files/FooBarEntity.php';

use Zend\ServiceManager\ServiceManager;
use NwBase\Entity\AbstractEntity;
use NwBase\DateTime\DateTime as NwDateTime;
use NwBase\DateTime\Date as NwDate;
use NwBaseTest\Entity\FooBarEntity;

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
        $poliforlismo = new \DateTime('09/24/2012');
        $valores = array('foo'=>'teste', 'bar'=>'blabla', 'poliforlismo' => $poliforlismo,);
        $obj = new \ArrayObject($valores);
        
        $myTest = new FooBarEntity();
        $return = $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
        $this->assertEquals($myTest, $return, "Não retorno sua propria instancia");
        
        $arrayCopy = $myTest->getArrayCopy();
        $expected = $valores;
        $expected['poliforlismo'] = $poliforlismo;
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
    
    public function testServiceLocatorAwareInterface()
    {
    	$services = new ServiceManager();
    	$entity = new FooBarEntity();
    	
    	$this->assertAttributeEmpty("_serviceLocator", $entity);
    	
    	$entity->setServiceLocator($services);
    	
    	// Service
    	$this->assertEquals($services, $entity->getServiceLocator());
    	$this->assertAttributeEquals($services, '_serviceLocator', $entity);
    }
    
    public function testConstrutorSetStoredAndGetStored()
    {
        $stored = true;
        $entity = new FooBarEntity(array(), $stored);
        
        $this->assertAttributeEquals(true, '_stored', $entity);
        $this->assertAttributeEquals(true, '_storedClean', $entity);
        $this->assertTrue($entity->getStored());
        
        $entity->exchangeArray(array());
        $this->assertAttributeEquals(false, '_storedClean', $entity);
    }
    
    public function testStoredCleanAndModified()
    {
        $entity = new FooBarEntity(array('foo' => 2, 'bar' => 'BAZ'), true);
        
        $expected = array(
            'bar' => 'baz',
        );
        
        $entity->setProperty('foo', '2');
        $this->assertFalse($entity->hasModified('foo'), "Deveria não modificar a propriedade");
        
        $entity->setProperty('bar', $expected['bar']);
        $this->assertTrue($entity->hasModified('bar'));
        
        $this->assertAttributeEquals($expected, '_modified', $entity);
        $this->assertEquals($expected, $entity->getModified());
        
        $entity->clearModified();
        $this->assertAttributeEquals(false, '_storedClean', $entity);
        $this->assertFalse($entity->hasModified('bar'));
    }
}
