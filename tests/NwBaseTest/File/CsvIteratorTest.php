<?php
namespace NwBaseTest\File;

use NwBase\File\CsvIterator;

class CsvIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $filename;
    
    protected $totLine = 4;
    
    public function setUp()
    {
        $content = '';
        for($x=0;$x<$this->totLine;$x++) {
            $content .= '"Line ' . $x . '";"Campo '.$x.'"' . PHP_EOL;
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
            class_exists($class = 'NwBase\File\CsvIterator'),
            "Classe {$class} não existe"
        );
    }
    
    public function testConstrunctAndIntances()
    {
        $delimiter = "|";
        $enclosure = '"';
        $escape = '\\';
        
        $iterator = new CsvIterator($this->filename, $delimiter, $enclosure, $escape);
        
        $this->assertInstanceOf('NwBase\File\FileIterator', $iterator);
        $this->assertAttributeSame($delimiter, 'delimiter', $iterator);
        $this->assertAttributeSame($enclosure, 'enclosure', $iterator);
        $this->assertAttributeSame($escape, 'escape', $iterator);
    }
    
    public function testMethodCurrentCsv()
    {
        $iterator = new CsvIterator($this->filename);
        $this->assertAttributeSame(";", 'delimiter', $iterator);
        $this->assertAttributeSame(null, 'enclosure', $iterator);
        $this->assertAttributeSame(null, 'escape', $iterator);
        
        foreach ($iterator as $x => $line)
        {
            $expected = array(
                'Line '.$x,
                'Campo '.$x,
            );
            
            $this->assertEquals($expected, $line);
        }
    }
}
