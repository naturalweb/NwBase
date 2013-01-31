<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Filter
 */
namespace NwBase\Filter;

use Zend\Filter\AbstractFilter;
use Locale;
use DateTime;

/**
 * Filtrar, removendo os caracteres adicionais, padrão espaço removendo no meio e no inicio e fim
 * 
 * @category NwBase
 * @package  NwBase\Filter
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class DateFormat extends AbstractFilter
{
    /**
     * @var array
     */
    protected $options = array(
        'format' => null,
    );
    
    /**
     * Sets filter options
     *
     * @param  string|array|Traversable $formatOrOptions
     */
    public function __construct($formatOrOptions = null)
    {
        if ($formatOrOptions !== null) {
            if (!is_array($formatOrOptions)
                    && !$formatOrOptions  instanceof Traversable)
            {
                $this->setFormat($formatOrOptions);
            } else {
                $this->setOptions($formatOrOptions);
            }
        }
    }
    
    /**
     * Sets the format option
     *
     * @param  string $format
     * @return StringTrim Provides a fluent interface
     */
    public function setFormat($format)
    {
        if (empty($format)) {
            $format = null;
        }
        $this->options['format'] = $format;
        return $this;
    }
    
    /**
     * Returns the format option
     *
     * @return string|null
     */
    public function getFormat()
    {
        return $this->options['format'];
    }
    
    /**
     * Filtra a Data criando um objeto DateTime
     */
    public function filter($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }
        
        $format = $this->getFormat();
        if (!$format) {
            return null;
        }
        
        $date = DateTime::createFromFormat($format, $value);
        if (!$date) {
            $date = null;
        }
        
        return $date;
    }
}