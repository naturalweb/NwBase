<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\MaskCpf;
use Zend\View\Renderer\PhpRenderer as View;

class MaskCpfTest extends \PHPUnit_Framework_TestCase
{
	protected $_maskCpf;
	
	protected $_numCpf = '29479392801';
	
	public function setUp()
	{
		$this->_maskCpf = new MaskCpf();
		$view = new View();
		$this->_maskCpf->setView($view);		
	}
	
	public function testMaskCpfValid()
	{	
		$maskCpf = $this->_maskCpf;
		
		$valid = $maskCpf($this->_numCpf);
		$this->assertEquals('294.793.928-01',$valid, "Formato de retorno Invalido");
	}
}
