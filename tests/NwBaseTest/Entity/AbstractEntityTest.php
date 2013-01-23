<?php

namespace NwBaseTest\Entity;

use NwBase\Entity\AbstractEntity;

require_once __DIR__ . '/../Tests/FooBarModel.php';
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
    
    public function testConstrutorContagemColunasArrayCopyAbstractEntity()
    {
        $valores = array('foo'=>'teste', 'bar'=>'blabla', 'poliforlismo' => '');
        $myTest = new FooBarEntity($valores);
        
        $cols = $myTest->cols();
        $this->assertEquals(count($valores), count($cols), "Numero de Colunas invalidas");
        $this->assertEquals(array_keys($valores), $cols, "Nomes de colunas invalidas");
        
        $arrayCopy = $myTest->getArrayCopy();
        $this->assertEquals($valores, $arrayCopy, "Array Copy, Valores de retorno Invalido");
        $this->assertSame($arrayCopy, $myTest->toArray(), "To Array, Valores de retorno invalido, deve ser como array copy");
    }
    
    /**
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
     */
    public function testExchangeArrayComArrayObject()
    {
        $valores = array('foo'=>'teste', 'bar'=>'blabla');
        $obj = new \ArrayObject($valores);
        
        $myTest = new FooBarEntity();
        $return = $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
        
        $this->assertEquals($myTest, $return, "Não retorno sua propria instancia");
    }
    
    /**
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
     */
    public function testExchangeArrayComObjetosImplementadoInterfaceEntity()
    {
        $valores = array('foo'=>'teste', 'bar'=>'blabla');
        $obj = new FooBarEntity($valores);
        
        $myTest = new FooBarEntity();
        $myTest->exchangeArray($obj);
        
        $this->assertAttributeEquals($valores['foo'], 'foo', $myTest, "Não setou um valor como deveria");
        $this->assertAttributeEquals($valores['bar'], 'bar', $myTest, "Não setou um valor como deveria");
    }
    
    /**
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
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
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
     */
    public function testRetornoMetodoToStringEmptyAbstract()
    {
        $mockTest = $this->getMockForAbstractClass('NwBase\Entity\AbstractEntity', array('toString'));
        
        $string = (string) $mockTest->toString();
        $this->assertEmpty($string);
    }
    
    /**
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
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
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
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
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
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
     * @depends testConstrutorContagemColunasArrayCopyAbstractEntity
     */
    public function testPoliformismoMetodoGet()
    {
        $poliforlismo = 'valor poli';
    
        $myTest = new FooBarEntity();
        $myTest->setPoliforlismo($poliforlismo);
        
        $return = $myTest->__get('poliforlismo');
        $this->assertEquals($poliforlismo, $return, "Não retornou o valor correto");
    }
    
    public function testValueDateTimeWithPhpDateTime()
    {
        $date = new \DateTime('2012-12-21 10:55:33');
        
        $stringDataTime = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATETIME, $date);
        $this->assertEquals('2012-12-21 10:55:33', $stringDataTime);
        
        $stringData = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATE, $date);
        $this->assertEquals('2012-12-21', $stringData);
        
        $stringTime = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::TIME, $date);
        $this->assertEquals('10:55:33', $stringTime);
    }
    
    public function testValueDateTimeWithValues()
    {
        $value = '1980-01-31 20:05:17';
        
        $stringDataTime = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATETIME, $value);
        $this->assertEquals('1980-01-31 20:05:17', $stringDataTime);
        
        $stringData = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATE, $value);
        $this->assertEquals('1980-01-31', $stringData);
        
        $stringTime = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::TIME, $value);
        $this->assertEquals('20:05:17', $stringTime);
    }
    
    public function testValueDateTimeVaueEmpty()
    {
        $value = '';
        
        $return = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATE, $value);
        $this->assertNull($return);
    }
    
    public function testValueDateTimeFormatInvalid()
    {
        $value = '1980-01-31 20:05:17';
    
        $return = AbstractEntity::valueDateTime('d/m/Y', $value);
        $this->assertNull($return);
    }
    
    public function testValueDateTimeValueInvalid()
    {
        $value = '21/02/2012';
    
        $return = AbstractEntity::valueDateTime(\NwBase\DateTime\DateTime::DATE, $value);
        $this->assertNull($return);
    }
}
