<?php

namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use NwBase\View\Helper\Mask;

/**
 * Formata a macara do Cep
 *
 * @package MY_View_Helper_Navigation_MaskCep
 * @author  Renato Moura <renato@naturalweb.com.br>
 * @since   1.0
 */
class MaskCep extends AbstractHelper
{
    /**
     * Método Principal
     * 
     * @param string $value Valor para Formatação
     * 
     * @return string Valor Formatado
     */
    public function __invoke($value)
    {
        $mask = new Mask();
        $mask->setView($this->view);
        
        $mask->setCapture('/^([0-9]{5})([0-9]{3})$/');
        $mask->setFormat('$1-$2');
        
        return $mask->render($value);
    }
}
