<?php

namespace NwBase\Entity;

use NwBase\Model\InterfaceModel;

interface InterfaceEntity
{
    public function exchangeArray($data);
    
    public function getArrayCopy();
    
    public function toArray();
    
    public function setProperty($property, $value);
    
    public function toString();
    
    public function preInsert(InterfaceModel $model);
    public function postInsert(InterfaceModel $model);
    
    public function preUpdate(InterfaceModel $model);
    public function postUpdate(InterfaceModel $model);
    
    public function preDelete(InterfaceModel $model);
    public function postDelete(InterfaceModel $model);
}
