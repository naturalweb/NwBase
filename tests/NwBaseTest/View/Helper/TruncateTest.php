<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\Truncate;
use Zend\View\Renderer\PhpRenderer as View;

class TruncateTest extends \PHPUnit_Framework_TestCase
{
	public function testTruncateWithoutWordsafe()
	{
		$truncate = new Truncate();
		$view = new View();
		$truncate->setView($view);
		
		$string = "123 5678 0abc";
		$stringFiltrada = $truncate($string, 6, false);
		
		$stringEsperada = "123 56";
		$stringEsperada .= "&hellip;";
		
		$this->assertEquals($stringEsperada, $stringFiltrada, "Não truncate a string como deveria");
	}
	
	
	public function testTruncateWithWordsafe()
	{
	    $truncate = new Truncate();
	    $view = new View();
	    $truncate->setView($view);
	
	    $string = "123 5678 0abc";
	    $stringFiltrada = $truncate($string, 10);
	
	    $stringEsperada = "123 5678";
	    $stringEsperada .= "&hellip;";
	
	    $this->assertEquals($stringEsperada, $stringFiltrada, "Não truncate a string como deveria");
	}
}
