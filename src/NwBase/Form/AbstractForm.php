<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Form;

use NwBase\AwareInterface\PrepareAwareInterface;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Classe abstrata para criação de Form
 * 
 * @category NwBase
 * @package  NwBase\Form
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractForm extends Form implements ServiceLocatorAwareInterface, PrepareAwareInterface
{
    use ServiceLocatorAwareTrait;
}
