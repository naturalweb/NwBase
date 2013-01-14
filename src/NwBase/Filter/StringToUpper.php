<?php
namespace NwBase\Filter;

use Zend\Filter\StringToUpper as Zend_Upper;

class StringToUpper extends Zend_Upper
{
    public function filter($value)
    {
        if (function_exists('mb_strtoupper')) {
            $encoding = $this->options['encoding'];
            if ($this->options['encoding'] === null) {
                $encoding = mb_internal_encoding();
            }
            return mb_strtoupper((string) $value, $encoding);
        }
        
        return strtoupper((string) $value);
    }
}