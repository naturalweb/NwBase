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
        $mockService = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockAbstractInputFilter->setServiceLocator($mockService);
    
        $this->assertEquals($mockService, $mockAbstractInputFilter->getServiceLocator());
    }
}
