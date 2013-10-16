<?php
namespace NwBaseTest\File;

use NwBase\File\FileIterator;

class FileIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $filename;
    
    protected $totLine = 4;
    
    public function setUp()
    {
        $content = '';
        for($x=0;$x<$this->totLine;$x++) {
            $content .= "Line " . $x . PHP_EOL;
        }
        
        $this->filename = tempnam('', '');
        file_put_contents($this->filename, trim($content));
    }
    
    public function tearDown()
    {
        unlink($this->filename);
    }
    
    protected function assertPreConditions()
    {
        $this->assertTrue(
            class_exists($class = 'NwBase\File\FileIterator'),
            "Classe {$class} não existe"
        );
    }
    
    public function testConstrunctAndIntances()
    {
        $iterator = new FileIterator($this->filename);
        
        $this->assertInstanceOf('\Iterator', $iterator);
        $this->assertInstanceOf('\Countable', $iterator);
        $this->assertAttributeEquals(null, 'key', $iterator);
        $this->assertAttributeEquals(null, 'lineCurrent', $iterator);
        $this->assertAttributeEquals(null, 'count', $iterator);
        $this->assertAttributeEquals($this->filename, "fileName", $iterator);
    }
    
    public function testCountable()
    {
        $iterator = new FileIterator($this->filename);
        
        $this->assertEquals($this->totLine, $iterator->count());
        $this->assertAttributeEquals($this->totLine, 'count', $iterator);
    }
    
    public function testLoopInInstance()
    {
        $iterator = new FileIterator($this->filename);
        
        $i=0;
        foreach ($iterator as $key => $line)
        {
            $this->assertEquals($i, $key);
            $this->assertEquals('Line ' . $i, $line);
            $i++;
        }
    }
}