<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Form\Element;

use Zend\Form\Element;

/**
 * Element do Formulario, para salvar a url de referencia,
 * para o futuro redirecionamento
 *
 * @category NwBase
 * @package  NwBase\Form\Element
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Referer extends Element
{
    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = array(
        'type' => 'hidden',
    );
    
    /**
     * Retrieve the element value, salvando o referer quanto não tiver value
     *
     * @return mixed
     */
    public function getValue()
    {
        if (!is_null($this->value)) {
            return $this->value;
        }
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            $value = $_SERVER['HTTP_REFERER'];
        } else {
            $value = '';
        }
        
        $this->setValue($value);
        return $value;
    }
}
