<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\Filter
 */
namespace NwBase\Filter;

use Zend\Filter\StringTrim;

/**
 * Filtrar, removendo os caracteres adicionais, padrão espaço removendo no meio e no inicio e fim
 * 
 * @category NwBase
 * @package  NwBase\Filter
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class TrimFull extends StringTrim
{
    /**
     * Metodo que filtra
     *
     * @param string $value Valor a ser filtrado
     * 
     * @return string
     */
    public function filter($value)
    {
        $carac = ' ';
        $pattern = "(".$carac."+)";
        
        return trim(preg_replace($pattern, $carac, $value), $carac);
    }
}
