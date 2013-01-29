<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause
 * @package   NwBase\Db\Sql
 */
namespace NwBase\Db\ResultSet;

use Zend\Db\ResultSet\AbstractResultSet;
use InvalidArgumentException;

class ResultSetPairs extends AbstractResultSet
{
    protected $_valuesDefault = array();
    protected $_columnKey     = null;
    protected $_columnValue   = null;
    
    /**
     * Constructor
     *
     * @param string $columnKey   Coluna com a Chave
     * @param string $columnValue Coluna com o Valor
     */
    public function __construct($columnKey, $columnValue, array $valuesDefault = array())
    {
        $this->setComlumnKey($columnKey);
        $this->setComlumnValue($columnValue);
        $this->setValuesDefault($valuesDefault);
    }
    
    public function setComlumnKey($columnKey)
    {
        $this->_columnKey = $columnKey;
    }
    
    public function setComlumnValue($columnValue)
    {
        $this->_columnValue = $columnValue;
    }
    
    public function setValuesDefault($valuesDefault)
    {
        $this->_valuesDefault = $valuesDefault;
    }
    
    /**
     * Iterator: retrieve current key
     *
     * @return mixed
     */
    public function key()
    {
        $data = parent::current();
        if (!isset($data[$this->_columnKey])) {
            throw new InvalidArgumentException("Coluna Key invalida, para montagem do pair");
        }
        
        return $data[$this->_columnKey];
    }
    
    public function current()
    {
        $data = parent::current();
        if (!isset($data[$this->_columnValue])) {
            throw new InvalidArgumentException("Coluna Value invalida, para montagem do pair");
        }
        
        return $data[$this->_columnValue];
    }
    
    /**
     * Cast result set to array of arrays
     *
     * @return array
     * @throws Exception\RuntimeException if any row is not castable to an array
     */
    public function toArray()
    {
        $return = $this->_valuesDefault;
        foreach ($this as $key => $row) {
            $return[$key] = $row;
        }
        return $return;
    }
}
