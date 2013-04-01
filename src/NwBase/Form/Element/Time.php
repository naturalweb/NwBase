<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
*
* @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
* @license   BSD-3-Clause
* @package   NwBase\Form\Element
*/

namespace NwBase\Form\Element;

use Zend\Form\Element\Time as TimeElement;

/**
 * Element do Formulario similiar ao time, somente alterando o attribute type
 *
 * @category NwBase
 * @package  NwBase\Form\Element
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Time extends TimeElement
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'text',
    );
}