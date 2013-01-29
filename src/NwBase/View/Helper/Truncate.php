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

use Zend\View\Helper\AbstractHelper;

/**
 * Truncate string cortando no tamanho solicitado
 *   ex: $this->truncateString($text, $length, [$wordsafe = true], [$escape = true])
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class Truncate extends AbstractHelper
{
    /**
     * Invoca o metodo helper
     *
     * @param string $text     Texto
     * @param int    $length   Tamanho a ser cortado
     * @param bool   $wordsafe Se mantem as palavras
     * @param bool   $escape   Escapa?
     * 
     * @return string
     */
    public function __invoke($text, $length, $wordsafe = true, $escape = true)
    {
        if (strlen($text) <= $length) {
            return $escape ? $this->view->escapeHtml($text) : $text;
        }
        
        if (!$wordsafe) {
            $text = substr($text, 0, $length);
        } else {
            $text   = substr($text, 0, $length + 1);
            $length = strrpos($text, ' ');
            $text   = substr($text, 0, $length);
        }
        
        return ($escape ? $this->view->escapeHtml($text) : $text) . '&hellip;';
    }
}
