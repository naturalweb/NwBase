<?php

namespace NwBaseTest\Tests;

use NwBase\Model\AbstractModel;

require_once __DIR__ . '/FooBarEntity.php';

class FooBarModel extends AbstractModel
{
    protected $tableName = 'table_test';
    
    protected function getEntityPrototype()
    {
        return new FooBarEntity();
    }
}
