<?php

namespace NwBaseTest\InputFilter;

use NwBase\InputFilter\AbstractInputFilter;

class AbstractInputFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testAbstractInputFilter()
    {
        $mockAbstractInputFilter = $this->getMockForAbstractClass('NwBase\InputFilter\AbstractInputFilter');
        $this->assertInstanceOf('Zend\ServiceManager\ServiceLocatorAwareInterface', $mockAbstractInputFilter);
        $this->assertInstanceOf('NwBase\AwareInterface\PrepareAwareInterface', $mockAbstractInputFilter);
        $this->assertInstanceOf('Zend\Db\Adapter\AdapterAwareInterface', $mockAbstractInputFilter);
    }
    
    public function testAbstractInputFilterSetService()
    {
        $mockAbstractInputFilter = $this->getMockForAbstractClass('NwBase\InputFilter\AbstractInputFilter');
        $mockService = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $return = $mockAbstractInputFilter->setServiceLocator($mockService);
        
        $this->assertAttributeEquals($mockService, 'serviceLocator', $mockAbstractInputFilter);
        $this->assertEquals($mockAbstractInputFilter, $return);
    }
    
    public function testAbstractInputFilterGetService()
    {
        $mockAbstractInputFilter = $this->getMockForAbstractClass('NwBase\InputFilter\AbstractInputFilter');
        $mockService             = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockAbstractInputFilter->setServiceLocator($mockService);
    
        $this->assertEquals($mockService, $mockAbstractInputFilter->getServiceLocator());
    }
    
    public function testAbstractInputFilterSetDbAdapter()
    {
        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockPlatform = $this->getMock('Zend\Db\Adapter\Platform\PlatformInterface');
        $mockAdapter = $this->getMockForAbstractClass(
            'Zend\Db\Adapter\Adapter',
            array($mockDriver, $mockPlatform)
        );
        
        $mockAbstractInputFilter = $this->getMockForAbstractClass('NwBase\InputFilter\AbstractInputFilter');
        $return = $mockAbstractInputFilter->setDbAdapter($mockAdapter);
    
        $this->assertAttributeEquals($mockAdapter, 'dbAdapter', $mockAbstractInputFilter);
        $this->assertEquals($mockAbstractInputFilter, $return);
    }
    
    public function testAbstractInputFilterGetDbAdapter()
    {
        $mockDriver = $this->getMock('Zend\Db\Adapter\Driver\DriverInterface');
        $mockPlatform = $this->getMock('Zend\Db\Adapter\Platform\PlatformInterface');
        $mockAdapter = $this->getMockForAbstractClass(
                'Zend\Db\Adapter\Adapter',
                array($mockDriver, $mockPlatform)
        );
        
        $mockAbstractInputFilter = $this->getMockForAbstractClass('NwBase\InputFilter\AbstractInputFilter');
        $mockAbstractInputFilter->setDbAdapter($mockAdapter);
    
        $this->assertEquals($mockAdapter, $mockAbstractInputFilter->getDbAdapter());
    }
}
