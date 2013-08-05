<?php 
namespace NwBaseTest\Db\Sql\Predicate;

use NwBase\Db\Sql\Predicate\NotILike;
use \PHPUnit_Framework_TestCase as TestCase;

class NotILikeTest extends TestCase
{
    public function testConstructionPredicateNotILike()
    {
        $predicate = new NotILike('foo', 'bar');
        $this->assertInstanceOf('Zend\Db\Sql\Predicate\Like', $predicate);
        $this->assertAttributeEquals('%1$s NOT ILIKE %2$s', 'specification', $predicate);
    }
}
