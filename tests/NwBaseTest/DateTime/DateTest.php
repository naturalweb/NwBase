<?php
namespace NwBaseTest\DateTime;

use NwBase\DateTime\Date;

class DateTest extends \PHPUnit_Framework_TestCase
{
    public function testDateTimeExtendsCorrect()
    {
        $obj = new Date();
        $this->assertInstanceOf('NwBase\DateTime\DateTime', $obj);
    }
    
    public function testDateTimeCreateStaticCorrectAndToString()
    {
        $time = "06/05/2010";
        $obj = new Date($time);
        $return = (string) $obj;
        
        $dateExpected = "2010-06-05";
        $this->assertEquals($dateExpected, $return, "Return to string invalido");
    }
}
