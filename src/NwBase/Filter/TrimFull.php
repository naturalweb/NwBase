<?php

namespace NwBase\Filter;

use Zend\Filter\StringTrim;

class TrimFull extends StringTrim
{
    /**
     * Filtrar, removendo os caracteres adicionais, padrão espaço removendo no meio e no inicio e fim
     *
     * @param string $value Valor a ser filtrado
     * 
     * @see Zend\Filter\Interface::filter()
     * 
     * @return string
     */
    public function filter($value)
    {
        $carac = ' ';
        $pattern = "(".$carac."+)";
        
        return trim(preg_replace($pattern, $carac, $value), $carac);
    }
}
