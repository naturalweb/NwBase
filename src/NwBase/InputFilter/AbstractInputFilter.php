<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\InputFilter
 */
namespace NwBase\InputFilter;

use NwBase\AwareInterface\PrepareAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\Adapter;

/**
 * Classe abstrata para criação de InputFilter
 * 
 * @category NwBase
 * @package  NwBase\InputFilter
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractInputFilter extends InputFilter implements ServiceLocatorAwareInterface, AdapterAwareInterface, PrepareAwareInterface
{
    /** 
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;
    
    /** 
     * @var Adapter
     */
    protected $dbAdapter = null;
    
    /**
     * Set serviceManager instance
     *
     * @param ServiceLocatorInterface $serviceLocator Objeto de Service
     * 
     * @return \NwBase\InputFilter\AbstractInputFilter
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
    
    /**
     * Set dbAdapter instance
     * 
     * @param Adapter $dbAdapter
     * 
     * @return \NwBase\InputFilter\AbstractInputFilter
     */
    public function setDbAdapter(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }
    
    /**
     * Retrieve dbAdapter instance
     * 
     * @return Adapter
     */
    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }
}
