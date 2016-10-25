<?php

namespace NwBaseTest\Model;

require_once __DIR__ . '/../../Entity/_files/FooBarEntity.php';

use NwBase\Model\AbstractModel;
use NwBaseTest\Entity\FooBarEntity;

class FooBarModel extends AbstractModel
{
    protected $_tableName = 'table_test';
    protected $sequenceName = 'table_test_foo_seq';

    protected function getEntityPrototype()
    {
        return new FooBarEntity();
    }
}
