<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\View
 * @subpackage Helper
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHtmlElement;

/**
 * Helper monta html de linhas de tabelas
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class RowsEmpty extends AbstractHtmlElement
{
    const TOTAL_ROWS_DEFAULT = 10;
    
    /**
     * Recebe a quantidade de registros e caso o numero seja menor do numero de linhas desejadas
     * Adiciona linhas de uma tabela em branco com a quantidade de colunas informada
     *
     * @param int $totalRegistros Qtd de Registros atuais
     * @param int $totalCols      Qtd de colunas
     * @param int $totalRows      Total de linhas desejadas
     *
     * @return string
     */
    public function __invoke($totalRegistros, $totalCols, $totalRows = self::TOTAL_ROWS_DEFAULT)
    {
        $totalRegistros = (int) $totalRegistros;
        $totalCols      = (int) $totalCols ? $totalCols : 1;
        $totalRows      = (int) $totalRows;
        
        $rows = PHP_EOL;
        
        for ($x = $totalRegistros; $x < $totalRows; $x++) {
            $rows .= '<tr>';
            for ($y=0; $y < $totalCols; $y++) {
                $rows .= '<td>&nbsp;</td>';
            }
            $rows .= '</tr>' . PHP_EOL;
        }
    
        return $rows;
    }
}
