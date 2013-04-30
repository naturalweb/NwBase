<?php
/**
 * NwManager
 * 
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Michelf;

/**
 * Utilização View Helper, utilizando o Markdown veja link
 *
 * @package NwBase\View\Helper
 * @author  Renato Moura <renato@naturalweb.com.br>
 * @link    http://michelf.ca/projects/php-markdown/
 */
class Markdown extends AbstractHelper
{
    /**
     * Metodo que invoca o view helper
     * 
     * @param string  $text   Texto a se formatar
     * @param boolean $escape Escape?
     * 
     * @return string
     */
    public function __invoke($text, $escape = true)
    {
        $markdown = new Michelf\Markdown();
        
        if ($escape) {
            $text = $this->view->escapeHtml($text);
        }
        
        $text = preg_replace("/ {0,1}\n/", "   \n", $text);
        $text = $markdown->transform($text);
        
        return $text;
    }
}
