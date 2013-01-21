<?php
namespace NwBase\DateTime;

use NwBase\DateTime\DateTime;

class Date extends DateTime
{
    public function toString()
    {
        return $this->format(DateTime::DATE);
    }
}
