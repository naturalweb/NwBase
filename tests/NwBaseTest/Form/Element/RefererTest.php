<?php
namespace NwBaseTest\Form\Element;

use PHPUnit_Framework_TestCase as TestCase;
use NwBase\Form\Element\Referer as RefererElement;

class RefererTest extends TestCase
{
    protected function tearDown()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            unset($_SERVER['HTTP_REFERER']);
        }
    }
 
    public function testElementAttributesAndValueDefault()
    {
        $element = new RefererElement();
        
        $this->assertInstanceOf('Zend\Form\Element', $element);
        $this->assertSame('', $element->getValue());
    }
    
    public function testValueWithReferer()
    {
        $referer = "http://localhost/teste";
        $_SERVER['HTTP_REFERER'] = $referer;
        
        $element = new RefererElement();
        
        $this->assertEquals($referer, $element->getValue());
    }
    
    public function testValueUpdateWithReferer()
    {
        $referer = "http://localhost/teste";
        $_SERVER['HTTP_REFERER'] = $referer;
        
        $element = new RefererElement();
        $value = "http://host.atual";
        $element->setValue($value);
        
        $this->assertEquals($value, $element->getValue());
    }
}
