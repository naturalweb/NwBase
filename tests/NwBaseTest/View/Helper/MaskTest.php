<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\Mask;
use Zend\View\Renderer\PhpRenderer as View;

class MaskTest extends \PHPUnit_Framework_TestCase
{
	protected $_helper;
    
	public function setUp()
	{
	    $this->_helper = new Mask();
	    $view = new View();
	    $this->_helper->setView($view);
	}
	
	public function assertPreConditions()
	{
	    $this->assertTrue(
	            class_exists($class = 'NwBase\View\Helper\Mask'),
	            "Classe NwBase\View\Helper\Mask not found " . $class
	    );
	}
	
	public function testMaskCorrect()
	{
	    $fone = "08007771234";
	    
	    $mask = $this->_helper;
	    
	    $mask()->setCapture("/([0-9]{4})([0-9]{3})([0-9]{4})/")->setFormat("$1 $2 $3");
		
		$return = $mask($fone);
		
		$this->assertEquals('0800 777 1234', $return, 'Formato de retorno Invalido');
	}
	
	public function testMaskCaptureIncorrect()
	{
	    $fone = "08007771234";
	    
	    $mask = $this->_helper;
	    
	    // Capture invalido. expressão regular invalida
	    $mask()->setCapture("/(0-9]/")
	           ->setFormat("$1 $2 $3");
	    
	    $return = $mask($fone);
	
	    $this->assertEquals($fone, $return, 'Formato de retorno Invalido');
	}
}
