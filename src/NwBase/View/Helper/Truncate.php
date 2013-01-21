<?php

namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Truncate extends AbstractHelper
{
    /**
     * Truncate input text
     * ex: $this->truncateString($text, $length, [$wordsafe = true], [$escape = true])
     *
     * @param string $text
     * @param int $length
     * @param bool $wordsafe
     * @param bool $escape
     * @return string
     */
    public function __invoke($text, $length, $wordsafe = true, $escape = true)
    {
        if (strlen($text) <= $length) {
            return $escape ? $this->view->escapeHtml($text) : $text;
        }
        
        if (!$wordsafe) {
            $text = substr($text, 0, $length);
        } else {
            $text   = substr($text, 0, $length + 1);
            $length = strrpos($text, ' ');
            $text   = substr($text, 0, $length);
        }
        
        return ($escape ? $this->view->escapeHtml($text) : $text) . '&hellip;';
    }
}
