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
     * Seta propriedade $finishParameters
     *
     * @param string|array $parameters parâmetros
     * 
     * @return \Zend\ProgressBar\Adapter\JsPush
     */
    public function setFinishParameters($parameters)
    {
        $this->finishParameters = $this->validateParameters($parameters);
        return $this;
    }
    
    /**
     * Defined by Zend\ProgressBar\Adapter\AbstractAdapter
     *
     * @param string|array $parameters parâmetros
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
    
    /**
     * Valida os parâmetros
     * 
     * @param string|array $parameters parâmetros
     * 
     * @return string JSON encoded object
     */
    protected function validateParameters($parameters)
    {
         if (is_array($parameters)) {
            $parameters = Json::encode($parameters);
            
        } elseif (is_scalar($parameters)) {
            $parameters = "'$parameters'";
            
        } else {
            $parameters = null;
        }
        
        return $parameters;
    }
    
    /**
     * Semelhante ao método finish porém recebe parâmetros
     *
     * @param string|array $parameters parâmetros
     *
     * @return void
     */
    public function encerra($parameters=null)
    {
        if (!is_null($parameters)) {
            $this->setFinishParameters($parameters);
        }
        
        $this->finish();
    }
    
    /**
     * Método sobrescrito para só imprimir se não for ambiente de teste
     */
    protected function _outputData($data)
    {
        if (APPLICATION_ENV != 'testing') {
            parent::_outputData($data);
        }
    }
}