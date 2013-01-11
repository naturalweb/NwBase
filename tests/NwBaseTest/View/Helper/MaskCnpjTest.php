<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\MaskCnpj;
use Zend\View\Renderer\PhpRenderer as View;

class MaskCnpjTest extends \PHPUnit_Framework_TestCase
{
	protected $_maskCnpj;
	
	protected $_numCnpj = '83632456000100';
	
	public function setUp()
	{
		$this->_maskCnpj = new MaskCnpj();
		$view = new View();
		$this->_maskCnpj->setView($view);		
	}
	
	public function testMaskCnpjValid()
	{
		$maskCnpj = $this->_maskCnpj;
		
		$valid = $maskCnpj($this->_numCnpj);
		$this->assertEquals('83.632.456/0001-00', $valid, 'Formato de retorno Invalido');
	}
}
