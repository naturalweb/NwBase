<?php
namespace NwBaseTest\View\Helper;

use NwBase\View\Helper\Markdown;
use Zend\View\Renderer\PhpRenderer as View;
use Zend\Dom;

Class MarkdownTest extends \PHPUnit_Framework_TestCase
{
    public function testMarkdown()
    {
        $view = new View();
        $helper = new Markdown();
        $helper->setView($view);
        
        $text = "**negrito** _italic_";
        $return = $helper($text);
        $dom = new Dom\Query($return);
        
        $result = $dom->execute("p strong");
        $this->assertEquals($result->current()->nodeValue, 'negrito');
        
        $result = $dom->execute("p em");
        $this->assertEquals($result->current()->nodeValue, 'italic');
    }
}
