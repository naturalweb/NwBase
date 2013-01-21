<?php
namespace NwBase\DateTime;

use NwBase\DateTime\DateTime;

class Time extends DateTime
{
    public function toString()
    {
        return $this->format(DateTime::TIME);
    }
}