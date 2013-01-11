<?php

namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\RowsEmpty;
use Zend\Dom;

class RowsEmptyTest extends \PHPUnit_Framework_TestCase
{
    public function testHelperRowsEmpty()
    {
        $this->assertTrue(
            class_exists($class = 'NwBase\View\Helper\RowsEmpty'),
            "Classe NwBase\View\Helper\RowsEmpty not found " . $class
        );
        
        $rowsEmpty = new RowsEmpty();
        
        $totalRecords = 2;
        $totalCols    = 5;
        $totalRows    = 15;
        
        $_html = $rowsEmpty($totalRecords, $totalCols, $totalRows);
        $dom = new Dom\Query($_html);
        
        $n_rows_empty = $totalRows - $totalRecords;
        $n_cols_empty = $totalCols * $n_rows_empty;
        
        $this->assertEquals($n_rows_empty, count($dom->execute('tr')), "Total Rows invalid");
        $this->assertEquals($n_cols_empty, count($dom->execute('tr td')), "Total Cols invalid");
    }
}
