<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\Entity
 * @subpackage Hydrator
 */
namespace NwBase\Entity\Hydrator;

use Zend\Stdlib\Hydrator\Reflection;
use NwBase\Entity\InterfaceEntity;
use NwBase\DateTime\DateTime as NwDateTime;

/**
 * Classe hidrata e extrai dados do objeto entity
 *
 * @category   NwBase
 * @package    NwBase\Entity
 * @subpackage Hydrator
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class HydratorEntity extends Reflection
{
    /**
     * Hydrate an object by populating public properties
     * Hydrates an object by setting public properties of the object.
     *
     * @param array  $data   Dados para inserir
     * @param object $object Objeto
     * 
     * @throws Exception\BadMethodCallException for a non-object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof InterfaceEntity) {
            throw new \BadMethodCallException(
                sprintf('%s expects the provided $object to be a InterfaceEntity)', __METHOD__)
            );
        }
        
        foreach ($data as $property => $value) {
            $property = strtolower($property);
            if (property_exists($object, $property)) {
                $value = $this->hydrateValue($property, $value);
                $object->setProperty($property, $value);
            }
        }
        
        return $object;
    }
    
    /**
     * Extract values from an object
     *
     * @param object $object Objeto para extrair dados
     * 
     * @return array
     */
    public function extract($object)
    {
        $values = parent::extract($object);
        $cols = $this->colsKeys($values);
        $cols = array_flip($cols);
        $data = array_intersect_key($values, $cols);
        
        $self = $this;
        array_walk(
            $data, 
            function (&$value, $name) use ($self) {
                if ($value instanceof NwDateTime) {
                    $value = (string) $value;
                    
                } elseif ($value instanceof \DateTime) {
                    $datetime = new NwDateTime();
                    $datetime->setTimestamp($value->getTimestamp());
                    $value = (string) $datetime;
                }
                
                $value = $self->extractValue($name, $value);
            }
        );
        
        return $data;
    }
    
    /**
     * Retorna array com as propriedade da entity
     * 
     * @param array $values Valores recebidos
     * 
     * @return array
     */
    protected function colsKeys($values)
    {
        $cols = array_keys($values);
        $cols = array_filter(
            $cols, 
            function ($key) {
                return preg_match("/^[^_]/", $key);
            }
        );
        $cols = array_values($cols);
        
        return $cols;
    }
}