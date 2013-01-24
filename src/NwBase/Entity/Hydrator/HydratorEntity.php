<?php
namespace NwBase\Entity\Hydrator;

use Zend\Stdlib\Hydrator\Reflection;
use NwBase\Entity\InterfaceEntity;
use NwBase\DateTime\DateTime as NwDateTime;

class HydratorEntity extends Reflection
{
    /**
     * Hydrate an object by populating public properties
     *
     * Hydrates an object by setting public properties of the object.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof InterfaceEntity) {
            throw new \BadMethodCallException(sprintf(
                    '%s expects the provided $object to be a InterfaceEntity)', __METHOD__
            ));
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
     * @param  object $object
     * @return array
     */
    public function extract($object)
    {
        $values = parent::extract($object);
        $cols = $this->colsKeys($values);
        $cols = array_flip($cols);
        $data = array_intersect_key($values, $cols);
        
        $self = $this;
        array_walk($data, function (&$value, $name) use ($self) {
            if ($value instanceof NwDateTime) {
                $value = (string) $value;
                
            } elseif ($value instanceof \DateTime) {
                $datetime = new NwDateTime();
                $datetime->setTimestamp($value->getTimestamp());
                $value = (string) $datetime;
            }
            
            $value = $self->extractValue($name, $value);
        });
        
        return $data;
    }
    
    protected function colsKeys($values)
    {
        $cols = array_keys($values);
        $cols = array_filter($cols, function ($key) {
            return preg_match("/^[^_]/", $key);
        });
        $cols = array_values($cols);
        
        return $cols;
    }
}