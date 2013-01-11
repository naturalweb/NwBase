<?php

namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHtmlElement;

/**
 * Cria Tabela
 * 
 * @param unknown_type $array   Array
 * @param unknown_type $colulas Colunas
 * @param unknown_type $attr    Attr
 * @param unknown_type $escape  Escapr
 * 
 * @return string
 */

class HtmlTable extends AbstractHtmlElement
{
/**
 * Cria Tabela
 * 
 * @param unknown_type $array   Array
 * @param unknown_type $colulas Colunas
 * @param unknown_type $attr    Attr
 * @param unknown_type $escape  Escapr
 * 
 * @return string
 */
    public function __invoke($array, $colulas = 1, $attr = array(), $escape = true )
    {
		if (!is_array($array) || !count($array)) {
			return '';
		}
		
		if ($colulas <= 0) {
			$colulas = 1;
		}
		
		$table_class = '';
		if (isset($attr['table_class'])) {
			$table_class = " class='".$attr['table_class']."'";
		}
		
		$td_class = '';
		if (isset($attr['td_class'])) {
			$td_class = " class='".$attr['td_class']."'";
		}
		
		$cols = 0;
		
		$out = "<table".$table_class.">";
		$out .= '<tr>';
		
		foreach ($array as $valor) {
		    if ($escape) {
		        $valor = $this->view->escapeHtml($valor);
		    }
		    
			if ($cols == $colulas ) {
				$out .= '</tr><tr>';
				$cols = 0;
			}
		
			$out .= "<td".$td_class.">";
			$out .= $valor;
			$out .= '</td>';
			
			$cols++;
    	}
		
		$rest_colunas = $colulas - $cols;
		for ($i=0;$i<$rest_colunas;$i++) {
			$out .= "<td".$td_class.">&nbsp;</td>";
		}
		
		$out .= '</tr>';
		$out .= '</table>';
		
		return $out;
    }
}
