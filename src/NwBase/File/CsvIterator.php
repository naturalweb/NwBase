<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\File;

/**
 * Efetua a leitura de um arquivo CSV, iplementa o iterator para percorrer as linhas do arquivo  
 *
 * @category NwBase
 * @package  NwBase\File
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class CsvIterator extends FileIterator
{
    /** 
     * @var string
     */
    protected $delimiter;
    
    /** 
     * @var bool
     */
    protected $escape;
    
    const DELIMITER_DEFAULT = ';';
    
    /**
     * Abre o arquivo para leitura e define a variaveis
     * 
     * @param string           $fileName  File Name
     * @param string[optional] $delimiter Separator Fields
     * @param string[optional] $enclosure Enclosure Fields 
     * @param string[optional] $escape    String de Escape
     * 
     * @throws \RuntimeException
     */
    public function __construct($fileName, $delimiter = null, $enclosure = null, $escape = null)
    {
        parent::__construct($fileName);
        
        $this->delimiter = (string) !is_null($delimiter) ? $delimiter : self::DELIMITER_DEFAULT;
        $this->enclosure = is_string($enclosure) ? $enclosure : null;
        $this->escape    = is_string($escape) ? $escape : null;
    }
    
    /**
     * Retorna a linha atual parseando csv, retornando o array
     * 
     * @return array
     */
    public function current()
    {
        if (is_string($this->lineCurrent))
        {
            $this->lineCurrent = str_getcsv($this->lineCurrent, $this->delimiter, $this->enclosure, $this->escape);
        }
        
        return $this->lineCurrent;
    }
}
