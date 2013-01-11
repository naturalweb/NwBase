<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\MaskCep;
use Zend\View\Renderer\PhpRenderer as View;

Class MaskCepTest extends \PHPUnit_Framework_TestCase
{
	protected $_maskCep;
	
	protected $_numCep = "09811100";
	
	public function setUp()
	{
		$this->_maskCep = new MaskCep();
		$view = new View();
		$this->_maskCep->setView($view);
	}	
	
	public function testMascCpfValid()
	{		
		$maskCep = $this->_maskCep;
		
		$valid = $maskCep($this->_numCep);
		$this->assertEquals('09811-100', $valid, 'Formato de retorno Invalido');
	}
}
