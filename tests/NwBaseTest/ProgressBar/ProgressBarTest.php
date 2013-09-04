<?php
namespace NwBaseTest\ProgressBar;

use NwBase\ProgressBar\ProgressBar;

class ProgressBarTest extends \PHPUnit_Framework_TestCase
{
    public function testEncerra()
    {
        $adapter = $this->getMock('NwBase\ProgressBar\Adapter\JsPush');
        $adapter->expects($this->once())
                ->method('encerra')
        		->will($this->returnValue(true));
        
        $progressBar = new ProgressBar($adapter);
        $progressBar->encerra();
    }
}