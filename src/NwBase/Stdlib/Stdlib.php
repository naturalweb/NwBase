<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Stdlib
 */
namespace NwBase\Stdlib;

use Zend\Stdlib\StringUtils as Zend_StringUtils;

/**
 * Extende a class StringUtils do Zend, adiciona o metodo
 * mb_str_pad(), itilizando as funções mb_string
 *
 * @category NwBase
 * @package  NwBase\Stdlib
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @see      Zend\Stdlib\StringUtils
 */
class StringUtils extends Zend_StringUtils
{
    /**
     * Completa uma string com um tamnho e caracteres especificos
     * 
     * @param string $input String inicial
     * @param int    $len   Tamanho esperado
     * @param string $char  Caracteres para completar
     * 
     * @return string
     */
    public static function mb_str_pad($input, $len, $char = ' ')
    {
        while (($len - mb_strlen($input, 'utf-8')) > 0) {
            $input .= $char;
            $qtd--;
        }
        
        $input = mb_substr($input, 0, $len, 'utf-8');
        
        return $input;
    }
}
