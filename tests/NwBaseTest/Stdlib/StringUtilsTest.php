<?php
namespace NwBaseTest\Stdlib\StringUtils;

use NwBase\Stdlib\StringUtils;

class StringUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testMbStrPad()
    {
        $input = "Têsté";
        $len = 10;
        $return = StringUtils::mb_str_pad($input, $len);
        $expected = 'Têsté     ';
        
        $this->assertEquals($expected, $return);
    }
    
    public function testMbStrPadWithChars()
    {
        $input = "Têsté";
        $len = 10;
        $return = StringUtils::mb_str_pad($input, $len, "ã1");
        $expected = 'Têstéã1ã1ã';
        
        $this->assertEquals($expected, $return);
    }
}