<?php 
namespace NwBaseTest\Db\Sql\Predicate;

use NwBase\Db\Sql\Predicate\ILike;
use \PHPUnit_Framework_TestCase as TestCase;

class ILikeTest extends TestCase
{
    public function testConstructionPredicateILike()
    {
        $predicate = new ILike('foo', 'bar');
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $predicate);
        $this->assertAttributeEquals('%1$s ILIKE %2$s', 'specification', $predicate);
    }
}
