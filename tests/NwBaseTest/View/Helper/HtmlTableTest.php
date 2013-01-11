<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\HtmlTable;
use Zend\Dom;
use Zend\View\Renderer\PhpRenderer as View;

Class HtmlTableTest extends \PHPUnit_Framework_TestCase
{
    protected $htmlTable;
    
    public function setUp()
	{		
		$this->htmlTable = new HtmlTable();
		$view = new View();
		$this->htmlTable->setView($view);
	}
	
	public function testHtmlTable()
	{		
		$valores = array("valor1","valor2","valor3","valor4","valor5","valor6","valor7","valor8","valor9");
		$attr = array('table_class' => 'teste', 'td_class' => 'campos');
		$colunas = 2;
		
		$htmlTable = $this->htmlTable;
		$tabela = $htmlTable($valores, $colunas, $attr);
		$dom = new Dom\Query($tabela);
		
		$n_linhas = ceil(count($valores) / $colunas);
		$n_celulas = $n_linhas * $colunas;
		
		$this->assertEquals(1, count($dom->execute("table.teste")), "Tabela nao renderizada");
		$this->assertEquals($n_linhas, count($dom->execute("table.teste tr")), "Numero de linhas esperado esta incorreto");
		$this->assertEquals($n_celulas, count($dom->execute("table.teste td")), "Numero de celulas esperado esta incorreto");
		$this->assertEquals($n_celulas, count($dom->execute("table.teste td.campos")), "Numero de celulas com classe campos esta incorreto");
	}
	
	public function testIgualColunasTable()
	{
		$valores = array("valor1","valor2","valor3","valor4","valor5","valor6","valor7","valor8","valor9");
		$attr = array('table_class' => 'teste', 'td_class' => 'campos');
		$colunas = 12;
	
		$htmlTable = $this->htmlTable;
		$tabela = $htmlTable($valores, $colunas, $attr);
		$dom = new Dom\Query($tabela);
	
		$this->assertEquals($colunas, count($dom->execute("table.teste td")), "Numero de celulas esperado esta incorreto");
		$this->assertEquals($colunas, count($dom->execute("table.teste td.campos")), "Numero de celulas com classe campos esta incorreto");
		$this->assertEquals(1, count($dom->execute("table.teste tr")), "Numero de linhas esperado esta incorreto");
	}
	
	public function testZeroColunasTable()
	{
	    $valores = array("valor1","valor2","valor3","valor4","valor5","valor6","valor7","valor8","valor9");
	    $attr = array('table_class' => 'teste_zero');
	    $colunas = 0;
	
	    $htmlTable = $this->htmlTable;
	    $tabela = $htmlTable($valores, $colunas, $attr);
	    $dom = new Dom\Query($tabela);
	
	    $this->assertEquals(count($valores), count($dom->execute("table.teste_zero td")), "Numero de celulas esperado esta incorreto");
	    $this->assertEquals(count($valores), count($dom->execute("table.teste_zero tr")), "Numero de linhas esperado esta incorreto");
	}
	
	public function testArrayVazioQualquerTipoVariavel()
	{
	    $valores = array();
	    $colunas = 5;
	    
	    $htmlTable = $this->htmlTable;
	    $tabela = $htmlTable($valores, $colunas);
	    	
	    $this->assertEmpty($tabela, "Deveria Retorar vazio");
	}
}
