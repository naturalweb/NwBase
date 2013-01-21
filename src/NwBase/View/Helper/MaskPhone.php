<?php

namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use NwBase\View\Helper\Mask;

/**
 * Formatação de Telefones
 * 
 * Auxiliar da Camada de Visualização
 * 
 * @package MY_View_Helper_Navigation_HtmlTable
 * @author  Renato Moura <renato@naturalweb.com.br>
 * @since   1.0 
 */
class MaskPhone extends AbstractHelper
{
    /**
     * Método Principal
     * 
     * @param string $value Valor para Formatação
     * 
     * @return string Valor Formatado
     */
    public function __invoke($value = null)
    {
        $mask = new Mask();
        $mask->setView($this->view);
        
        $mask->setCapture('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/');
        $mask->setFormat('($1) $2-$3');
        
        return $mask->render($value);
    }
}
