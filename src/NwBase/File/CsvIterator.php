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
     * @var bool
     */
    protected $isHeader;
    
    /**
     * @var array
     */
    protected $headers;
    
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
    public function __construct($fileName, $isHeader = null, $delimiter = null, $enclosure = null, $escape = null)
    {
        parent::__construct($fileName);
        
        $this->isHeader = (boolean) $isHeader;
        $this->delimiter = (string) !is_null($delimiter) ? $delimiter : self::DELIMITER_DEFAULT;
        $this->enclosure = is_string($enclosure) ? $enclosure : null;
        $this->escape    = is_string($escape) ? $escape : null;
    }
    
    /**
     * Retorna a linha atual parseando csv, retornando o array
     * 
     * @return array
     */
    protected function getLine()
    {
        $line = str_getcsv(parent::getLine(), $this->delimiter, $this->enclosure, $this->escape);
        $line = array_map("trim", $line);
        
        return $line;
    }
    
    /**
     * Retorna o headers
     *
     * @return array
     */
    public function getHeaders()
    {
        if ($this->headers === null) {
            if (!$this->isHeader) {
                return $this->headers = array();
            }
            
            $tell = ftell($this->fileHandle);
            fseek($this->fileHandle, 0);
            
            $headers = $this->getLine();
            
            fseek($this->fileHandle, $tell);
            unset($tell);
            
            $this->headers = $headers;
        }
        
        return $this->headers;
    }
    
    /**
     * Chama o getHeaders e inicia da segunda linha
     * 
     * @see \NwBase\File\FileIterator::rewind()
     */
    public function rewind()
    {
        if ( $this->isHeader ) {
            $this->getHeaders();
            fseek($this->fileHandle, 1);
            $this->lineCurrent = $this->getLine();
            $this->key = 0;
        } else {
            parent::rewind();
        }
    }
}
