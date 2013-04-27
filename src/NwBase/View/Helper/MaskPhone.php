<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\View\Helper;

use Zend\View\Helper\AbstractHelper;
use NwBase\View\Helper\Mask;

/**
 * Formata a Mascara de Saida de Telefones
 *
 * @category   NwBase
 * @package    NwBase\View
 * @subpackage Helper
 * @author     Renato Moura <renato@naturalweb.com.br>
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
