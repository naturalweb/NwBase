<?php
namespace NwBase\Filter;

use Zend\Filter\StringToLower as Zend_Lower;

class StringToLower extends Zend_Lower
{
    public function filter($value)
    {
        if (function_exists('mb_strtolower')) {
            $encoding = $this->options['encoding'];
            if ($this->options['encoding'] === null) {
                $encoding = mb_internal_encoding();
            }
            return mb_strtolower((string) $value, $encoding);
        }
        
        return strtolower((string) $value);
    }
}
