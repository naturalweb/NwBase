<?php

namespace NwBaseTest\Filter;

use NwBase\Filter\Transliteration;
use Zend\Filter\FilterInterface;

class TransliterationTest extends \PHPUnit_Framework_TestCase
{
    public function testStaticFilter()
    {
        $stringComAcento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ°ºª$";
        $stringFiltrada = Transliteration::staticFilter($stringComAcento);
        $stringEsperada = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBBaaaaaaaceeeeiiiidnoooooouuuuYYBYRrooaS";
    
        $this->assertEquals($stringEsperada, $stringFiltrada, "Acento nao foi retirado");
    }
    
	public function testFiltraCaracterAcentuado()
	{
		$transliteration = new Transliteration();
		
		$stringComAcento = "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ°ºª$";
		$stringFiltrada = $transliteration->filter($stringComAcento);
		$stringEsperada = "AAAAAAACEEEEIIIIDNOOOOOOUUUUYBBaaaaaaaceeeeiiiidnoooooouuuuYYBYRrooaS";
		
		$this->assertEquals($stringEsperada, $stringFiltrada, "Acento nao foi retirado");
	}
}
