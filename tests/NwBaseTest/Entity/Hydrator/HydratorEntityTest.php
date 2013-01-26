<?php

namespace NwBaseTest\Entity;

require_once __DIR__ . '/../_files/FooBarEntity.php';

use NwBase\Entity\Hydrator\HydratorEntity;
use NwBaseTest\Entity\FooBarEntity;

class HydratorEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \BadMethodCallException
     */
    public function testMethodHydrateWithObjectInvalid()
    {
        $hydrator = new HydratorEntity();
        $object = new \stdClass();
        $object->teste = 'foo';
        $data = array('teste' => 'bar');
        $hydrator->hydrate($data, $object);
    }
}
