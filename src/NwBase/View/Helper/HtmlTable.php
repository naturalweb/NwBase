<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHtmlElement;

/**
 * Monta uma tabela atraves de valores de um array, definindo a quantidade de colunas
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class HtmlTable extends AbstractHtmlElement
{
    /**
     * Metodo invoca o helper
     * 
     * @param array   $array   Valores
     * @param int     $colulas Quantidade de colunas
     * @param array   $attr    Atributos da tabela e colunas
     * @param boolean $escape  Escapar string?
     * 
     * @return string
     */
    public function __invoke(array $array, $colulas = 1, array $attr = array(), $escape = true)
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
        for ($i=0; $i < $rest_colunas; $i++) {
            $out .= "<td".$td_class.">&nbsp;</td>";
        }
        
        $out .= '</tr>';
        $out .= '</table>';
        
        return $out;
    }
}
