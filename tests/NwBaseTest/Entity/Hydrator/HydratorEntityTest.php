<?php

namespace NwBaseTest\Entity;

use NwBase\Entity\Hydrator\HydratorEntity;
use NwBaseTest\Tests\FooBarEntity;

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
