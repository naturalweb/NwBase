<?php

namespace NwBase\Test;

use Zend\Authentication\Result;
use Zend\Dom;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Execute a DOM/XPath query
     *
     * @param  string $path
     * @return array
     */
    private function _query($html, $path)
    {
        if (empty($html)) {
            return null;
        }
        
        $dom = new Dom\Query($html);
        return $dom->execute($path);
    }
    
    /**
     * Assert against DOM selection
     *
     * @param  string $path CSS selector path
     * @param  string $message
     * @return void
     */
    public function assertQuery($html, $path, $message = '')
    {
        $result = $this->_query($html, $path);
        $matched = count($result);
        $this->assertTrue($matched > 0, $message);
    }
    
    /**
     * Assert not against DOM selection
     *
     * @param  string $path CSS selector path
     * @param  string $message
     * @return void
     */
    public function assertNotQuery($html, $path, $message = '')
    {
        $result = $this->_query($html, $path);
        $matched = count($result);
        $this->assertFalse($matched > 0, $message);
    }
    
    
    /**
     * Assert against DOM selection; should contain exact number of nodes
     *
     * @param  string $path CSS selector path
     * @param  string $count Number of nodes that should match
     * @param  string $message
     * @return void
     */
    public function assertQueryCount($html, $path, $count, $message = '')
    {
        $result = $this->_query($html, $path);
        $this->assertEquals($count, count($result), $message);
    }
    
    /**
     * Assert against DOM selection; node should NOT contain content
     *
     * @param  string $html Html de retorno da função
     * @param  string $path CSS selector path
     * @param  string $match content that should NOT be contained in matched nodes
     * @param  string $message
     * @return void
     */
    public function assertNotQueryContentContains($html, $path, $match, $message = '')
    {
        $result = $this->_query($html, $path);
        $matched = $this->_matchContent($result, $match);
        $this->assertFalse($matched, $message);
    }
    
    /**
     * Assert against DOM selection; node should contain content
     *
     * @param  string $html Html de retorno da função
     * @param  string $path CSS selector path
     * @param  string $match content that should be contained in matched nodes
     * @param  string $message
     * @return void
     */
    public function assertQueryContentContains($html, $path, $match, $message = '')
    {
        $result = $this->_query($html, $path);
        $matched = $this->_matchContent($result, $match);
        $this->assertTrue($matched, $message);
    }
    
    /**
     * Check to see if content is matched in selected nodes
     *
     * @param  Zend_Dom_Query_Result $result
     * @param  string $match Content to match
     * @return bool
     */
    protected function _matchContent($result, $match)
    {
        $match = (string) $match;
    
        if (0 == count($result)) {
            return false;
        }
    
        foreach ($result as $node) {
            $content = $this->_getNodeContent($node);
            if (strstr($content, $match)) {
                return true;
            }
        }
        
        return false;
    }


    /**
     * Get node content, minus node markup tags
     *
     * @param  DOMNode $node
     * @return string
     */
    protected function _getNodeContent(\DOMElement $node)
    {
        if ($node instanceof DOMAttr) {
            return $node->value;
        } else {
            $doc     = $node->ownerDocument;
            $content = $doc->saveXML($node);
            $tag     = $node->nodeName;
            $regex   = '|</?' . $tag . '[^>]*>|';
            return preg_replace($regex, '', $content);
        }
    }
}
