<?php
namespace NwBaseTest\File;

use NwBase\File\CsvIterator;

class CsvIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected $filename;
    
    protected $totLine = 4;
    
    protected function makeFile($isHeader = false, $hasWhiteSpace = false)
    {
        $this->dropFile();
        
        $content = '';
        if ($isHeader) {
            $content = 'num_line;"name_field"' . PHP_EOL;
        }
        
        $whiteSpace = $hasWhiteSpace === true ? "  ": "";
        
        for($x=0;$x<$this->totLine;$x++) {
            $content .= '"Line '.$x.$whiteSpace.'";"Campo '.$x.$whiteSpace.'"' . PHP_EOL;
        }
        
        $this->filename = tempnam('', '');
        file_put_contents($this->filename, trim($content));
        
        return $this->filename;
    }
    
    protected function dropFile()
    {
        if($this->filename && file_exists($this->filename)) {
            unlink($this->filename);
        }
    }
    
    public function tearDown()
    {
        $this->dropFile();
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
        $this->makeFile();
        
        $isHeader = 1;
        $delimiter = "|";
        $enclosure = '"';
        $escape = '\\';
        
        $iterator = new CsvIterator($this->filename, $isHeader, $delimiter, $enclosure, $escape);
        
        $this->assertInstanceOf('NwBase\File\FileIterator', $iterator);
        $this->assertAttributeSame(true, 'isHeader', $iterator);
        $this->assertAttributeSame($delimiter, 'delimiter', $iterator);
        $this->assertAttributeSame($enclosure, 'enclosure', $iterator);
        $this->assertAttributeSame($escape, 'escape', $iterator);
    }
    
    public function testMethodGetLineCsv()
    {
        $this->makeFile(false, true);
        
        $iterator = new CsvIterator($this->filename);
        $this->assertAttributeSame(false, 'isHeader', $iterator);
        $this->assertAttributeSame(";", 'delimiter', $iterator);
        $this->assertAttributeSame(null, 'enclosure', $iterator);
        $this->assertAttributeSame(null, 'escape', $iterator);
        
        foreach ($iterator as $x => $line) {
            $expected = array(
                'Line '.$x,
                'Campo '.$x,
            );
            
            $this->assertEquals($expected, $line);
        }
    }
    
    public function testMethodGetHeadersAndRewind()
    {
        $this->makeFile(true);
        
        $iterator = new CsvIterator($this->filename, true);
        $this->assertAttributeSame(true, 'isHeader', $iterator);
        
        $expected = array("num_line", "name_field");
        $this->assertAttributeSame(true, 'isHeader', $iterator);
        $this->assertSame($expected, $iterator->getHeaders());
    }
}
