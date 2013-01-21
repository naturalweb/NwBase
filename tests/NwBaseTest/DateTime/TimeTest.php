<?php
namespace NwBaseTest\DateTime;

use NwBase\DateTime\Time;

class TimeTest extends \PHPUnit_Framework_TestCase
{
    public function testDateTimeExtendsCorrect()
    {
        $obj = new Time();
        $this->assertInstanceOf('NwBase\DateTime\DateTime', $obj);
    }
    
    public function testDateTimeCreateStaticCorrectAndToString()
    {
        $time = "15:33:01";
        $obj = new Time($time);
        $return = (string) $obj;
        
        $timeExpected = "15:33:01";
        $this->assertEquals($timeExpected, $return, "Return to string invalido");
    }
}
