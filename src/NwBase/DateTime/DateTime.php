<?php
namespace NwBase\DateTime;

class DateTime extends \DateTime
{
    const DATETIME = 'Y-m-d H:i:s';
    const DATE = 'Y-m-d';
    const TIME = 'H:i:s';
    
    public function toString()
    {
        return $this->format(self::DATETIME);
    }
    
    public function __toString()
    {
        return $this->toString();
    }
    
    public static function createFromFormat($format, $time, $timezone = null)
    {
        $ext_dt = false;
        
        $datetime = parent::createFromFormat($format, $time);
        if ($datetime) {
            $ext_dt = new self();
            $ext_dt->setTimestamp($datetime->getTimestamp());
        }
        
        return $ext_dt;
    }
}
