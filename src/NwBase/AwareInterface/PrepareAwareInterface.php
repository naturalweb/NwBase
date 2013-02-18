<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\AwareInterface
 */
namespace NwBase\AwareInterface;

/**
 * Interface utilizadas em form e input filter para preparação dos elements
 * 
 * @category NwBase
 * @package  NwBase\AwareInterface
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
interface PrepareAwareInterface
{
    public function prepareElements();
}
