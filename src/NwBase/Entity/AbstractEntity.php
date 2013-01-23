<?php

namespace NwBase\Entity;

use NwBase\Model\InterfaceModel;
use NwBase\DateTime\DateTime;

abstract class AbstractEntity implements InterfaceEntity
{
    public function __construct($data = array())
    {
        $this->exchangeArray($data);
    }
    
    public function exchangeArray($data)
    {
        if (is_object($data) ) {
            if ( $data instanceof InterfaceEntity || method_exists($data, 'toArray') ) {
                $data = $data->toArray();
                
            } elseif ( $data instanceof \ArrayAccess || method_exists($data, 'getArrayCopy') ) {
                $data = $data->getArrayCopy();
                
            } else {
                $data = get_object_vars($data);
            }
        }
        
        if (is_array($data) ) {
            foreach ($data as $key => $value) {
                $key = strtolower($key);
                if (property_exists($this, $key)) {
                    $this->_setProperty($key, $value);
                }
            }
        }
    
        return $this;
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
    
    public function cols()
    {
        $vars = get_object_vars($this);
        $cols = array_keys($vars);
        $cols = array_filter(
            $cols,
            function ($key) {
                return preg_match("/^[^_]/", $key);
            }
        );
        $cols = array_values($cols);
    
        return $cols;
    }
    
    public function getArrayCopy()
    {
        $vars = get_object_vars($this);
        $cols = $this->cols();
        $cols = array_flip($cols);
        $data = array_intersect_key($vars, $cols);
    
        return $data;
    }
    
    final public function toArray()
    {
        return $this->getArrayCopy();
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
    
    final private function _setProperty($property, $value)
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
                return $this->_setProperty($property, array_shift($args));
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
        switch ($format) {
            case DateTime::DATETIME:
                $nameObj = "DateTime";
                break;
            case DateTime::DATE:
                $nameObj = "Date";
                break;
            case DateTime::TIME:
                $nameObj = "Time";
                break;
            default:
                return null;
        }
        
        if ($value instanceof \DateTime) {
            $datetime = $value;
            
        } elseif (empty($value)) {
            return null;
            
        } else {
            try {
                $datetime = new DateTime($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return $datetime->format($format);
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
