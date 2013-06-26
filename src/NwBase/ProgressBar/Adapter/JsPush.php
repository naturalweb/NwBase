<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\ProgressBar\Adapter;

use Zend\ProgressBar\Adapter\JsPush as ZendJsPush;
use Zend\Json\Json;

/**
 * Adapter do ProgressBar, para definir parametro do metodo finish
 *
 * @category   NwBase
 * @package    NwBase\ProgressBar\Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class JsPush extends ZendJsPush
{
    /**
     * Parametros para o metodo finish
     *
     * @var string
     */
    protected $finishParameters;
    
    /**
     * Set the finish method name
     *
     * @param  string $methodName
     * @return \Zend\ProgressBar\Adapter\JsPush
     */
    public function setFinishParameters($parameters)
    {
        if (is_array($parameters)) {
            $parameters = Json::encode($parameters);
            
        } elseif (!is_scalar($parameters)) {
            $parameters = null;
        }
        
        $this->finishParameters = $parameters;
        
        return $this;
    }
    
    /**
     * Defined by Zend\ProgressBar\Adapter\AbstractAdapter
     * Com os parametros de finish
     *
     * @return void
     */
    public function finish()
    {
        if ($this->finishMethodName === null) {
            return;
        }
        
        $data = '<script type="text/javascript">'
        . 'parent.' . $this->finishMethodName . '(' . $this->finishParameters . ');'
        . '</script>';
        
        $this->_outputData($data);
    }
}