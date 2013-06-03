<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 * 
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Decoda;

/**
 * Utilização View Helper, utilizando o Decoda, para formatar texto em BBCode veja link
 *
 * @package NwBase\View\Helper
 * @author  Renato Moura <renato@naturalweb.com.br>
 * @link    https://github.com/milesj/Decoda
 */
class BBCode extends AbstractHelper
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
        if ($escape) {
            $text = $this->view->escapeHtml($text);
            $text = str_replace('&quot;', '"', $text);
        }
        
        $code = new Decoda\Decoda($text);
        $code->defaults();
        
        return $code->parse();
    }
}
