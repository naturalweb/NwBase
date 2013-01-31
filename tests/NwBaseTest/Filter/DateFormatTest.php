<?php

namespace NwBaseTest\Filter;

use NwBase\Filter\DateFormat;
use DateTime;

class DateFormatTest extends \PHPUnit_Framework_TestCase
{
    public function testDateFormatValid()
    {
        $format = 'd/m/Y';
        $time = '31/12/2012';
        $expectedDate = DateTime::createFromFormat($format, $time);
        
        $filterDate = new DateFormat($format);
        $actualDate = $filterDate->filter($time);
        
        $this->assertInstanceOf('\DateTime', $actualDate);
        $this->assertEquals($expectedDate, $actualDate);
    }
    
    public function testDateFormatInvalid()
    {
        $format = 'd/m/Y';
        $time = '2012-12-31';
        
        $filterDate = new DateFormat($format);
        $actualDate = $filterDate->filter($time);
        
        $this->assertNull($actualDate);
    }
    
    public function testDateWithNotFormat()
    {
        $time = '31/12/2012';
    
        $filterDate = new DateFormat();
        $actualDate = $filterDate->filter($time);
        
        $this->assertNull($actualDate);
    }
    
    public function testDateFormatWithObject()
    {
        $time = new DateTime('2013-12-31');
    
        $filterDate = new DateFormat();
        $actualDate = $filterDate->filter($time);
    
        $this->assertInstanceOf('\DateTime', $actualDate);
        $this->assertEquals($time, $actualDate);
    }
}