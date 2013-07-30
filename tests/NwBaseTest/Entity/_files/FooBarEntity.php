<?php

namespace NwBaseTest\Entity;

use NwBase\Entity\AbstractEntity;

class FooBarEntity extends AbstractEntity
{
    protected $foo;
    protected $bar;
    protected $_interno;
    protected $poliforlismo;
    
    public function setPoliforlismo($val)
    {
        $this->poliforlismo = $val;
        
        return true;
    }
    
    public function getPoliforlismo()
    {
        return $this->poliforlismo;
    }
    
    public function toString()
    {
        $return = sprintf("FOO: %s, BAR: %s", $this->foo, $this->bar);
        return $return;
    }
    
    /**
     * Retorna id da entidade
     *
     * @return int
     */
    public function getId()
    {
        return $this->foo;
    }
    
    /**
     * Retorna descrição da entidade
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->bar;
    }
}
