<?php

namespace NwBase\Entity;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use NwBase\Model\InterfaceModel;
use NwBase\DateTime\DateTime as NwDateTime;
use NwBase\Entity\Hydrator\HydratorEntity;

abstract class AbstractEntity implements InterfaceEntity, ServiceLocatorAwareInterface
{
    /**
     * @var HydratorInterface
     */
    protected $_hydrator = null;
    
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $_serviceLocator = null;
    
    public function __construct($data = array())
    {
        $this->_hydrator = new HydratorEntity;
        $this->exchangeArray($data);
    }
    
    public function exchangeArray($data)
    {
        if (is_object($data) ) {
            if (is_callable(array($data, 'getArrayCopy'))) {
                $data = $data->getArrayCopy();
                
            } else {
                $data = get_object_vars($data);
            }
        }
        
        if (is_array($data) ) {
            $this->getHydrator()->hydrate($data, $this);
        }
        
        return $this;
    }
    
    public function getArrayCopy()
    {
        return $this->getHydrator()->extract($this);
    }
    
    final public function toArray()
    {
        return $this->getArrayCopy();
    }
    
    /**
     * Metodo do retorna dos dados como um string
     *
     * @return string
     */
    public function toString()
    {
        return '';
    }
    
    /**
     * Metodo magico para retornar a string quando o metodo é chamado como string
     *
     * @return string
     */
    final public function __toString()
    {
        return $this->toString();
    }
    
    final public function __set($property, $value)
    {
        $msg = sprintf('Set direto da propriedade não permitido, Utilize o metodos "set"', $property);
        throw new \InvalidArgumentException($msg);
    }
    
    final public function setProperty($property, $value)
    {
        // Valida se existe a propriedade, caso contrario gera a excessão
        if (!property_exists($this, $property)) {
            $msg = sprintf('Propriedade "%s" inválida', $property);
            throw new \InvalidArgumentException($msg);
        }
        
        // Verifica se existe um metodo "SET" da propriedade, e executa a mesma
        $words = array_map('ucfirst', explode("_", $property));
        $method = "set";
        $method .= implode("", $words);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
    
        // Seta a propriedade conforme a necessidade
        if (is_string($value)) {
            $value = trim($value);
        }
    
        $value = !empty($value) ? $value : null;
        $this->$property = $value;
    
        return $this;
    }
    
    final public function __get($property)
    {
        // Valida se existe a propriedade, caso contrario gera a excessão
        if (!property_exists($this, $property)) {
            $msg = sprintf('Propriedade "%s" inválida', $property);
            throw new \InvalidArgumentException($msg);
        }
    
        // Veririca se existe um metodo "GET" da propriedade, e executa a mesma
        $words = array_map('ucfirst', explode("_", $property));
        $method = "get";
        $method .= implode("", $words);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
    
        return $this->$property;
    }
    
    final public function __call($method, $args)
    {
        if ( ! preg_match('/^(set|get)(([A-Z]{1})([A-Za-z0-9]+))/', $method, $matches) ) {
            $msg = sprintf('Metodo "%s" inválido', $method);
            throw new \BadMethodCallException($msg);
        }
    
        $property = preg_replace('/([A-Z])/', '_\\1', $matches[2]);
        $property = trim($property, '_');
        $property = strtolower($property);
    
        switch ($matches[1]) {
            case 'set':
                $args = (array) $args;
                return $this->setProperty($property, array_shift($args));
                break;
            case 'get':
                return $this->__get($property);
                break;
            default:
                throw new \RuntimeException("Erro interno na expressão regular. method __call");
                break;
        }
    }
    
    public static function valueDateTime($format, $value)
    {
        if (empty($value)) {
            return null;
        }
        
        switch ($format) {
            case NwDateTime::DATETIME:
                $nameObj = "NwBase\\DateTime\\DateTime";
                break;
            case NwDateTime::DATE:
                $nameObj = "NwBase\\DateTime\\Date";
                break;
            case NwDateTime::TIME:
                $nameObj = "NwBase\\DateTime\\Time";
                break;
            default:
                return null;
        }
        
        if ($value instanceof NwDateTime) {
            $datetime = $value;
        
        } elseif ($value instanceof \DateTime) {
            $datetime = new $nameObj();
            $datetime->setTimestamp($value->getTimestamp());
        } else {
            try {
                $datetime = new $nameObj($value);
            } catch (\Exception $e) {
                $datetime = null;
            }
        }
        
        return $datetime;
    }
    
    /**
     * Get the hydrator to use for each row object
     *
     * @return HydratorInterface
     */
    public function getHydrator()
    {
        return $this->_hydrator;
    }
    
    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->_serviceLocator = $serviceLocator;
    }
    
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->_serviceLocator;
    }
    
    public function preInsert(InterfaceModel $model)
    {
    }
    
    public function postInsert(InterfaceModel $model)
    {
    }
    
    public function preUpdate(InterfaceModel $model)
    {
    }
    
    public function postUpdate(InterfaceModel $model)
    {
    }
    
    public function preDelete(InterfaceModel $model)
    {
    }
    
    public function postDelete(InterfaceModel $model)
    {
    }
}
