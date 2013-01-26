<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 * 
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @package   NwBase\DateTime
 */
namespace NwBase\DateTime;

/**
 * Representação para tratamento de hora
 * 
 * @category NwBase
 * @package  NwBase\DateTime
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Time extends DateTime
{
    /**
     * String de saida no formato de time
     * 
     * @see NwBase\DateTime.DateTime::__toString()
     * @return string
     */
    public function __toString()
    {
        return $this->format(DateTime::TIME);
    }
}
