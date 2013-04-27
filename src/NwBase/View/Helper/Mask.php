<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Formata a Mascara de Saida diversas
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class Mask extends AbstractHelper
{
    /**
     * Valor Inicial de Captura
     * @var string
     */
    protected $_capture = null;

    /**
     * Valor Inicial para Formato de Saída
     * @var string
     */
    protected $_format = null;

    /**
     * Método Principal
     *
     * @param string $value Valor para Formatação
     * 
     * @return string Valor Formatado
     */
    public function __invoke($value = null)
    {
        /* Acesso Direto ao Auxiliar */
        if ($value === null) {
            return $this;
        }
        
        return $this->render($value);
    }
    
    /**
     * Configura a Formatação de Saída
     * 
     * @param string $format Formato para Renderização do Conteúdo
     * 
     * @return string Valor Formatado
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * Informa a Formatação de Saída
     *
     * @return string Valor Solicitado
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * Configura o Padrão de Captura
     *
     * @param string $capture Padrão para Captura de Conteúdo
     * 
     * @return string Valor Formatado
     */
    public function setCapture($capture)
    {
        $this->_capture = $capture;
        return $this;
    }

    /**
     * Informa o Padrão de Captura
     *
     * @return string Valor Solicitado
     */
    public function getCapture()
    {
        return $this->_capture;
    }
    
    /**
     * Function Render
     * 
     * @param unknown_type $value value
     * 
     * @return string|unknown
     */
    public function render($value)
    {
        /* Acesso aos Dados de Filtragem */
        $format  = $this->getFormat();
        $capture = $this->getCapture();
        
        /* Resultado da Renderização */
        $result = @preg_replace($capture, $format, $value);
        if ($result === null) {
            $result = $value;
        }
        
        $result = $this->view->escapeHtml($result);
        
        return $result;
    }
}
