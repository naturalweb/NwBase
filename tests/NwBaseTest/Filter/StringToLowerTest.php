<?php
namespace NwBaseTest\Filter;

use NwBase\Filter\StringToLower;

class StringToLowerTest extends \PHPUnit_Framework_TestCase
{
    protected $_filter;
    
    public function setUp()
    {
        ini_set("mbstring.internal_encoding", "");
        $this->_filter = new StringToLower();
    }
    
    public function assertPreConditions()
    {
        $this->assertInstanceOf("Zend\Filter\StringToLower", $this->_filter);
    }
    
    /**
     * Test do NwBase
     */
    public function testBasicEncodigDefault()
    {
        if (!function_exists('mb_internal_encoding')) {
            $this->markTestSkipped("Function 'mb_internal_encoding' not available");
        }
    
        ini_set("mbstring.internal_encoding", "utf-8");
        $filter = $this->_filter;
    
        $valuesExpected = array(
                "FÔÁ BÃR"       => "fôá bãr",
                "NENHUM ACENTO" => "nenhum acento",
        );
    
        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter($input));
        }
    }
    
    /**
     * Ensures that the filter follows expected behavior
      *
     * @return void
     */
    public function testBasic()
    {
        $filter = $this->_filter;
        $valuesExpected = array(
            'string' => 'string',
            'aBc1@3' => 'abc1@3',
            'A b C' => 'a b c'
        );

        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter($input));
        }
    }

    /**
     * Ensures that the filter follows expected behavior with
     * specified encoding
     *
     * @return void
     */
    public function testWithEncoding()
    {
        $filter = $this->_filter;
        $valuesExpected = array(
            'Ü' => 'ü',
            'Ñ' => 'ñ',
            'ÜÑ123' => 'üñ123'
        );

        try {
            $filter->setEncoding('UTF-8');
            foreach ($valuesExpected as $input => $output) {
                $this->assertEquals($output, $filter($input));
            }
        } catch (\Zend\Filter\Exception\ExtensionNotLoadedException $e) {
            $this->assertContains('mbstring is required', $e->getMessage());
        }
    }

    /**
     * @return void
     */
    public function testFalseEncoding()
    {
        if (!function_exists('mb_strtolower')) {
            $this->markTestSkipped('mbstring required');
        }

        $this->setExpectedException('\Zend\Filter\Exception\InvalidArgumentException', 'is not supported');
        $this->_filter->setEncoding('aaaaa');
    }

    /**
     * @ZF-8989
     */
    public function testInitiationWithEncoding()
    {
        $valuesExpected = array(
            'Ü' => 'ü',
            'Ñ' => 'ñ',
            'ÜÑ123' => 'üñ123'
        );

        try {
            $filter = new StringToLower(array('encoding' => 'UTF-8'));
            foreach ($valuesExpected as $input => $output) {
                $this->assertEquals($output, $filter($input));
            }
        } catch (\Zend\Filter\Exception\ExtensionNotLoadedException $e) {
            $this->assertContains('mbstring is required', $e->getMessage());
        }
    }

    /**
     * @ZF-9058
     */
    public function testCaseInsensitiveEncoding()
    {
        $filter = $this->_filter;
        $valuesExpected = array(
            'Ü' => 'ü',
            'Ñ' => 'ñ',
            'ÜÑ123' => 'üñ123'
        );

        try {
            $filter->setEncoding('UTF-8');
            foreach ($valuesExpected as $input => $output) {
                $this->assertEquals($output, $filter($input));
            }

            $this->_filter->setEncoding('utf-8');
            foreach ($valuesExpected as $input => $output) {
                $this->assertEquals($output, $filter($input));
            }

            $this->_filter->setEncoding('UtF-8');
            foreach ($valuesExpected as $input => $output) {
                $this->assertEquals($output, $filter($input));
            }
        } catch (\Zend\Filter\Exception\ExtensionNotLoadedException $e) {
            $this->assertContains('mbstring is required', $e->getMessage());
        }
    }

    /**
     * @group ZF-9854
     */
    public function testDetectMbInternalEncoding()
    {
        if (!function_exists('mb_internal_encoding')) {
            $this->markTestSkipped("Function 'mb_internal_encoding' not available");
        }

        $this->assertEquals(mb_internal_encoding(), $this->_filter->getEncoding());
    }
}