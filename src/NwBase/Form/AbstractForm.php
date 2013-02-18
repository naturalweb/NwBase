<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Form
 */
namespace NwBase\Form;

use NwBase\AwareInterface\PrepareAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Classe abstrata para criação de Form
 * 
 * @category NwBase
 * @package  NwBase\Form
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractForm implements ServiceLocatorAwareInterface, PrepareAwareInterface
{
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;
    
    /**
     * Set serviceManager instance
     *
     * @param ServiceLocatorInterface $serviceLocator Objeto de Service
     * 
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
