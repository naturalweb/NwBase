<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\ProgressBar;

use Zend\ProgressBar\ProgressBar as ZendProgressBar;

/**
 * Classe ProgressBar
 *
 * @category   NwBase
 * @package    NwBase\ProgressBar\Adapter
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class ProgressBar extends ZendProgressBar
{
    /**
     * Semelhante ao método finish porém recebe parâmetros
     * 
     * @param array|string $parameters
     * 
     * @return void
     */
    public function encerra($parameters=null)
    {
        if ($this->persistenceNamespace !== null) {
            unset($this->persistenceNamespace->isSet);
        }
    
        $this->adapter->encerra($parameters);
    }
}