<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 * 
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\DateTime
 */
namespace NwBase\DateTime;

/**
 * Representação para tratamento de time
 * 
 * @category NwBase
 * @package  NwBase\DateTime
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Date extends DateTime
{
    /**
     * String de saida no formato de data
     * 
     * @see NwBase\DateTime.DateTime::__toString()
     * @return string
     */
    public function __toString()
    {
        return $this->format(self::DATE);
    }
}
