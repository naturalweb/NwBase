<?php
namespace NwBaseTest\ProgressBar;

use NwBase\ProgressBar\ProgressBar;

class ProgressBarTest extends \PHPUnit_Framework_TestCase
{
    public function testEncerra()
    {
        $adapter = $this->getMock('NwBase\ProgressBar\Adapter\JsPush', array('encerra'));
        $adapter->expects($this->once())
                ->method('encerra');
        
        $progressBar = new ProgressBar($adapter);
        $progressBar->encerra();
    }
}