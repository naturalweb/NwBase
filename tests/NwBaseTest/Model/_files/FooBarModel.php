<?php

namespace NwBaseTest\Model;

require_once __DIR__ . '/../../Entity/_files/FooBarEntity.php';

use NwBase\Model\AbstractModel;
use NwBaseTest\Entity\FooBarEntity;

class FooBarModel extends AbstractModel
{
    protected $_tableName = 'table_test';
    
    protected function getEntityPrototype()
    {
        return new FooBarEntity();
    }
}
