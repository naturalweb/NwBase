<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Entity
 */
namespace NwBase\Entity;

use NwBase\Model\InterfaceModel;

/**
 * Interface das Entity
 *
 * @category   NwBase
 * @package    NwBase\Entity
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
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
