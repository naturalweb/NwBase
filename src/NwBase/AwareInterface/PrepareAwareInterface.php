<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\AwareInterface;

/**
 * Interface utilizadas em form e input filter para preparação dos elements
 * 
 * @category NwBase
 * @package  NwBase\AwareInterface
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
interface PrepareAwareInterface
{
    /**
     * Metodo responsavel por preparar os elementos para adicionar as classes,
     * ex: Form, InputFilter
     * 
     * @return mixed
     */
    public function prepareElements();
}
