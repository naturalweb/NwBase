<?php

namespace NwBaseTest\ProgressBar\Adapter;

use \PHPUnit_Framework_TestCase as TestCase;
use \Zend\Json\Json;

class JsPushTest extends TestCase
{
    public function testSetFinishParametersWithArray()
    {
        $parameters = array('query' => 'foobar');
        
        $adapter = new JsPushStub();
        
        $return = $adapter->setFinishParameters($parameters);
        
        $this->assertAttributeEquals(\Zend\Json\Json::encode($parameters), 'finishParameters', $adapter);
        $this->assertEquals($adapter, $return, "Deveria retornar a propria instancia");
    }
    
    public function testSetFinishParametersWithString()
    {
        $parameters = 'foobar';
    
        $adapter = new JsPushStub();
    
        $return = $adapter->setFinishParameters($parameters);
    
        $this->assertAttributeEquals($parameters, 'finishParameters', $adapter);
        $this->assertEquals($adapter, $return, "Deveria retornar a propria instancia");
    }
    
    public function testSetFinishParametersNotWithScalar()
    {
        $parameters = new \stdClass();
    
        $adapter = new JsPushStub();
    
        $return = $adapter->setFinishParameters($parameters);
    
        $this->assertAttributeEquals(null, 'finishParameters', $adapter);
        $this->assertEquals($adapter, $return, "Deveria retornar a propria instancia");
    }
    
    public function testMethodFinishWithParameters()
    {
        $parameters = array('query' => 'foobar');
        
        $adapter = new JsPushStub(array('finishMethodName' => 'Zend\ProgressBar\ProgressBar\Finish', 'finishParameters' => $parameters));
        $adapter->notify(0, 2, 0.5, 1, 1, 'status');
        $adapter->finish();
        $output = $adapter->getLastOutput();
        
        $matches = preg_match('#<script type="text/javascript">parent.'. preg_quote('Zend\ProgressBar\ProgressBar\Finish') . '\((.*?)\);</script>#', $output, $result);
        
        $this->assertEquals(1, $matches);
        
        $data = json_decode($result[1], true);
        
        $this->assertEquals('foobar', $data['query']);
    }
    
    public function testValidateParametersWithArray()
    {
        $parameters = array('foo' => 'bar');
        $expected = Json::encode($parameters);
        
        $adapter = new JsPushStub();
        $actual = $adapter->setFinishParameters($parameters);
        
        $this->assertAttributeEquals($expected, 'finishParameters', $actual);
    }
    
    public function testValidateParametersWithString()
    {
        $parameters = 'foobar';
    
        $adapter = new JsPushStub();
        $actual = $adapter->setFinishParameters($parameters);
    
        $this->assertAttributeEquals($parameters, 'finishParameters', $actual);
    }
}

class JsPushStub extends \NwBase\ProgressBar\Adapter\JsPush
{
    protected $_lastOutput = null;

    public function getLastOutput()
    {
        return $this->_lastOutput;
    }

    protected function _outputData($data)
    {
        $this->_lastOutput = $data;
    }
}