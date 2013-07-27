<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\InputFilter;

use NwBase\AwareInterface\PrepareAwareInterface;
use Zend\InputFilter\InputFilter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Db\Adapter\Adapter;

/**
 * Classe abstrata para criação de InputFilter
 * 
 * @category NwBase
 * @package  NwBase\InputFilter
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @abstract
 */
abstract class AbstractInputFilter extends InputFilter implements ServiceLocatorAwareInterface, PrepareAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    /** 
     * @var Adapter
     */
    protected $dbAdapter = null;
        
    /**
     * Set dbAdapter instance
     * 
     * @param Adapter $dbAdapter Adapter do Database
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
        if (!$this->dbAdapter) {
            $this->dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        }
        
        return $this->dbAdapter;
    }
}
