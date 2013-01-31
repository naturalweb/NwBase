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
 * Extende a class StringUtils do Zend
 *
 * @category NwBase
 * @package  NwBase\Stdlib
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @see      Zend\Stdlib\StringUtils
 */
class StringUtils extends Zend_StringUtils
{
    /**
     * Completa uma string com um tamanho e caracteres especificos
     * Utiliza as funções mb_string para considerar acentuação
     * 
     * @param string $input String inicial
     * @param int    $len   Tamanho esperado
     * @param string $char  Caracteres para completar
     * 
     * @return string
     */
    public static function mb_str_pad($input, $len, $chars = ' ')
    {
        while (($len - mb_strlen($input, 'utf-8')) > 0) {
            $input .= $chars;
        }
        
        $input = mb_substr($input, 0, $len, 'utf-8');
        
        return $input;
    }
}
