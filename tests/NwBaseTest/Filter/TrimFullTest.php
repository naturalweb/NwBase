<?php

namespace NwBaseTest\Filter;

use NwBase\Filter\TrimFull;
use Zend\Filter\FilterInterface;

class TrimFullTest extends \PHPUnit_Framework_TestCase
{	
	public function testStringComVariosEspacosAleatorios()
	{
		$trimFull = new TrimFull();
		
		$stringComEspaco = "   Edson    Horacio       Junior   ";
		$stringSemEspaco = $trimFull->filter($stringComEspaco);
		$stringEsperada = "Edson Horacio Junior";
		
		$this->assertEquals($stringEsperada, $stringSemEspaco, "Nem todos os espacos foram removidos da string");
	}
	
}
