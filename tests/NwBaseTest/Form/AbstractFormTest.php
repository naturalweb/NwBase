<?php

namespace NwBaseTest\Form;

use NwBase\Form\AbstractForm;

class AbstractFormTest extends \PHPUnit_Framework_TestCase
{
    public function testAbstractForm()
    {
        $mockAbstractForm = $this->getMockForAbstractClass('NwBase\Form\AbstractForm');
        $this->assertInstanceOf('Zend\ServiceManager\ServiceLocatorAwareInterface', $mockAbstractForm);
    }
    
    public function testAbstractFormSetService()
    {
        $mockAbstractForm = $this->getMockForAbstractClass('NwBase\Form\AbstractForm');
        $mockService = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $return = $mockAbstractForm->setServiceLocator($mockService);
        
        $this->assertAttributeEquals($mockService, 'serviceLocator', $mockAbstractForm);
        $this->assertEquals($mockAbstractForm, $return);
    }
    
    public function testAbstractFormGetService()
    {
        $mockAbstractForm = $this->getMockForAbstractClass('NwBase\Form\AbstractForm');
        $mockService = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockAbstractForm->setServiceLocator($mockService);
    
        $this->assertEquals($mockService, $mockAbstractForm->getServiceLocator());
    }
}
