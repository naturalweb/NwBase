<?php
namespace NwBaseTest\Filter;

use NwBase\Filter\StringToUpper;

class StringToUpperTest extends \PHPUnit_Framework_TestCase
{
    protected $_filter;
    
    public function setUp()
    {
        ini_set("mbstring.internal_encoding", "");
        $this->_filter = new StringToUpper();
    }
    
    public function assertPreConditions()
    {
        $this->assertInstanceOf("Zend\Filter\StringToUpper", $this->_filter);
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
                "fôá bãr"       => "FÔÁ BÃR",
                "nenhum Acento" => "NENHUM ACENTO",
        );
    
        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($output, $filter($input));
        }
    }
    
    /// -------- Teste do Zend Framework -----------
    /**
     * Ensures that the filter follows expected behavior
     *
     * @return void
     */
    public function testBasic()
    {
        $filter = $this->_filter;
        $valuesExpected = array(
                'STRING' => 'STRING',
                'ABC1@3' => 'ABC1@3',
                'A b C' => 'A B C'
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
                'ü' => 'Ü',
                'ñ' => 'Ñ',
                'üñ123' => 'ÜÑ123'
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
                'ü' => 'Ü',
                'ñ' => 'Ñ',
                'üñ123' => 'ÜÑ123'
        );
    
        try {
            $filter = new StringToUpper(array('encoding' => 'UTF-8'));
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
                'ü' => 'Ü',
                'ñ' => 'Ñ',
                'üñ123' => 'ÜÑ123'
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