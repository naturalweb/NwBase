<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
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
     * @param string|array|Traversable $formatOrOptions Options or Format
     */
    public function __construct($formatOrOptions = null)
    {
        if ($formatOrOptions !== null) {
            if (!is_array($formatOrOptions) && !$formatOrOptions  instanceof Traversable) {
                $this->setFormat($formatOrOptions);
            } else {
                $this->setOptions($formatOrOptions);
            }
        }
    }
    
    /**
     * Sets the format option
     *
     * @param string $format Formato
     * 
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
     * 
     * @return DateTime
     */
    public function filter($value)
    {
        if ($value instanceof DateTime) {
            return $value;
        }
        
        $format = $this->getFormat();
        if (!$format) {
            return '';
        }
        
        $date = DateTime::createFromFormat($format, $value);
        
        $errors = DateTime::getLastErrors();
        if (!$date || $errors['warning_count'] > 0 ||  $errors['error_count'] > 0) {
            $date = '';
        }
                
        return $date;
    }
}