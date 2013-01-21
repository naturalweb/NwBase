<?php
namespace NwBase;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Module NwBase
 * 
 * @category NwBase
 * @package  NwBase
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @license  NaturalWeb <http://www.naturalweb.com.br>
 *
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
     * Definição das urls para o autoload
     * 
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Configurações do module
     * 
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
