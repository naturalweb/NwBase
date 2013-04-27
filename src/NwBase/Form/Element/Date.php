<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Form\Element;

use Zend\Form\Element\Date as DateElement;

/**
 * Element do Formulario similiar ao date, somente alterando o attribute type
 *
 * @category NwBase
 * @package  NwBase\Form\Element
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Date extends DateElement
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
