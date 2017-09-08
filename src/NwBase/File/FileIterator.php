<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\File;

/**
 * Efetua a leitura de um arquivo, iplementa o iterator para percorrer as linhas do arquivo
 *
 * @category NwBase
 * @package  NwBase\File
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class FileIterator implements \Iterator, \Countable
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var resource
     */
    protected $fileHandle;

    /**
     * @var string
     */
    protected $lineCurrent;

    /**
     * @var int
     */
    protected $key;

    /**
     * @var int
     */
    protected $count;

    /**
     * Abre o arquivo para leitura
     *
     * @param string $fileName Path
     *
     * @throws \RuntimeException
     */
    public function __construct($fileName)
    {
        $this->fileName = (string) $fileName;

        if (!$this->fileHandle = fopen($fileName, 'r')) {
            throw new \RuntimeException('Couldn\'t open file "' . $fileName . '"');
        }
    }

    /**
     * Contagem de linhas
     *
     * @return int
     */
    public function count()
    {
        if (is_null($this->count)) {
            $this->count = 0;

            $tell = ftell($this->fileHandle);
            fseek($this->fileHandle, 0);

            while (!feof($this->fileHandle) && false !== fgets($this->fileHandle)) {
                $this->count += 1;
            }

            fseek($this->fileHandle, $tell);
            unset($tell);
        }

        return $this->count;
    }

    /**
     * Inicia a leitura do arquivo do inicio
     *
     * @return void
     */
    public function rewind()
    {
        fseek($this->fileHandle, 0);
        $this->lineCurrent = $this->getLine();
        $this->key = 0;
    }

    /**
     * Retorna o indice atual
     *
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Valida se havera proxima linha
     *
     * @return bool
     */
    public function valid()
    {
        return !feof($this->fileHandle) && false !== $this->lineCurrent;
    }

    /**
     * Retorna a linha atual
     *
     * @return string
     */
    public function current()
    {
        return $this->lineCurrent;
    }

    /**
     * Busca a proxima linha
     *
     * @return void
     */
    public function next()
    {
        if ($this->valid()) {
            $this->lineCurrent = $this->getLine();
            $this->key++;
        }
    }

    /**
     * Le alinha no arquivo, formata o encodig caso seja necessario
     *
     * @return string
     */
    protected function getLine()
    {
        $encoding = 'UTF-8';

        $line = self::removeUtf8Bom(fgets($this->fileHandle));

        if (mb_detect_encoding($line, $encoding, true) != $encoding) {
            $line = utf8_encode($line);
        }

        return trim($line);
    }

    /**
     * Ao destruir fecha o arquivo
     *
     * @return void
     */
    public function __destruct()
    {
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }

    /**
     * Remove UTF8 BOM, que é uma espécie de identificador que informa que o dado está em UTF8
     *
     * @param  string $text
     *
     * @return string
     */
    public static function removeUtf8Bom($text)
    {
        $bom      = pack('H*','EFBBBF');
        $replaced = preg_replace("/^{$bom}/", '', $text);

        return $replaced ?: $text;
    }
}
