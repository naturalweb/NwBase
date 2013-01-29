<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright  Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license    BSD-3-Clause
 * @package    NwBase\View
 * @subpackage Helper
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use NwBase\View\Helper\Mask;

/**
 * Formata a Mascara de Saida de CNPJ
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
 */
class MaskCnpj extends AbstractHelper
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
                
        $mask->setCapture('/^([0-9]{2,3})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2})$/');
        $mask->setFormat('$1.$2.$3/$4-$5');
        
        return $mask->render($value);
    }
}
