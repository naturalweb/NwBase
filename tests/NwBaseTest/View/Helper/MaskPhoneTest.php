<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\MaskPhone;
use Zend\View\Renderer\PhpRenderer as View;

Class MaskPhoneTest extends \PHPUnit_Framework_TestCase
{
	protected $_maskPhone;
	
	protected $_numTelefone1 = '1143516308';
	protected $_numTelefone2 = '11975127329';	
				
	public function setUp()
	{
		$this->_maskPhone = new MaskPhone();
		$view = new View();
		$this->_maskPhone->setView($view);		
	}
	
	public function testMaskPhoneNumbe()
	{				
		$maskPhone = $this->_maskPhone;
		
		$valid = $maskPhone($this->_numTelefone1);	
		$this->assertEquals('(11) 4351-6308', $valid, 'Formato de retorno Invalido');
		
		$valid = $maskPhone($this->_numTelefone2);
		$this->assertEquals('(11) 97512-7329', $valid, 'Formato de Retorno Invalido');
	}
	
}
