<?php
namespace NwBaseTest\DateTime;

use NwBase\DateTime\DateTime;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    public function testDateTimeExtendsCorrect()
    {
        $obj = new DateTime();
        $this->assertInstanceOf('\DateTime', $obj);
    }
    
    public function testDateTimeCreateStaticCorrectAndToString()
    {
        $time = "22/12/2012 10:50:01";
        $obj = DateTime::createFromFormat('d/m/Y H:i:s', $time);
        $return = (string) $obj;
        
        $timeExpected = "2012-12-22 10:50:01";
        $objExpected = new DateTime($timeExpected);
        
        $this->assertEquals($objExpected, $obj, "Create obj invalid");
        $this->assertEquals($timeExpected, $return, "Return to string invalido");
    }
    
    public function testDateTimeDataInvalid()
    {
        $datetime = "01/12/2012 05:45:01";
        $obj = DateTime::createFromFormat(DateTime::DATETIME, $datetime);
        
        $this->assertFalse($obj, "Deveria Retornar false");
    }
}
