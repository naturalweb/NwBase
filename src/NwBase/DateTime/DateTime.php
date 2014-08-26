<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\DateTime;

/**
 * Representação para tratamento de data e hora
 *
 * @category NwBase
 * @package  NwBase\DateTime
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class DateTime extends \DateTime
{
    const DATETIME = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';
    const TIME = 'H:i:s';
    const BR_DATETIME = 'd/m/Y H:i:s';
    const BR_DATE     = 'd/m/Y';

    /**
     * String de saida no formato de time
     *
     * @see NwBase\DateTime.DateTime::toString()
     * @return string
     */
    public function __toString()
    {
        return $this->format(self::DATETIME);
    }

    /**
     * Factory da propria classe
     *
     * @param string           $format   Formato
     * @param string|\DateTime $time     Valor da Data
     * @param object           $timezone Timezone
     *
     * @return \NwBase\DateTime\DateTime
     */
    public static function createFromFormat($format, $time, $timezone = null)
    {
        $extDt = false;

        $datetime = parent::createFromFormat($format, $time);
        if ($datetime) {
            $extDt = new self();
            $extDt->setTimestamp($datetime->getTimestamp());
        }

        return $extDt;
    }
}
