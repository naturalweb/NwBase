<?php
namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\BBCode as BBCodeViewHelper;
use Zend\View\Renderer\PhpRenderer as View;
use Zend\Dom;

Class BBCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testViewHelperBBCode()
    {
        $view = new View();
        $helper = new BBCodeViewHelper();
        $helper->setView($view);
        
        $text = '[align="center"][b]negrito[/b] [i]italic[/i][/align]';
        $return = $helper($text);
        $dom = new Dom\Query($return);
        
        $result = $dom->execute("div b");
        $this->assertEquals($result->current()->nodeValue, 'negrito');
        
        $result = $dom->execute("div i");
        $this->assertEquals($result->current()->nodeValue, 'italic');
    }
}
