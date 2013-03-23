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
     * No Contrutor Adicionado o filtra para Extração,
     * As propriedade privadas identificadas pelo (_) são ignoradas
     */
    public function __construct()
    {
        parent::__construct();
    
        // Filtra as propriedade privadas identificadas pelo (_)
        $this->getFilter()->addFilter('propertysEntity', function($property){
            return (boolean) preg_match("/^[^_]/", $property);
        });
    }
    
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
}