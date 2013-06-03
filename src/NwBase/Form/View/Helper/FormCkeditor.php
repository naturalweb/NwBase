<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\Form\View\Helper;

use Zend\Form\View\Helper\FormTextarea;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;

/**
 * Classe abstrata para criação de Form
 *
 * @category NwBase
 * @package  NwBase\Form\View\Helper
 * @author   Renato Moura <renato@naturalweb.com.br>
 * @todo     CRIAR TESTES - PHPUNIT
 */
class FormCkeditor extends FormTextarea
{
    /**
     * @var int Quantidade de instacias impressas
     */
    static $instances = 0;
        
    /**
     * Render a form <textarea> element from the provided $element
     *
     * @param ElementInterface $element Element Form
     * 
     * @throws Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element)
    {
        self::$instances += 1;
        
        if (self::$instances == 1) {
            $this->getView()->headScript()->appendFile("/assets/ckeditor/ckeditor.js");
        }
        
        $nameEditor = $element->getAttribute('id');
        
        if (empty($nameEditor)) {
            $nameEditor = $element->getAttribute('name');
        }
        
        $tipoEditor = null;
        if ($element->hasAttribute('editor')) {
            $tipoEditor = $element->getAttribute('editor');
        }
        
        if (!empty($nameEditor)) {
            $optionsEditor = ", {" . $this->tipoEditor($tipoEditor) . "}";
            
            $script = "CKEDITOR.replace('{$nameEditor}'{$optionsEditor});";
            
            $this->getView()->inlineScript()->appendScript($script);
        }
        
        return parent::render($element);
    }
    
    /**
     * Define o tipo do editor, e suas configurações predefinidas
     * 
     * @param string $tipo Tipo do toolbar
     * 
     * @return string
     */
    private function tipoEditor($tipo)
    {
        $config = '';
        
        switch ($tipo) {
            case 'Basic':
            case 'Standard':
                $config  = "
                    toolbar : '{$tipo}',
                    removePlugins: 'bbcode'
                ";
                break;
            default:
            case 'BBCode':
                $config  = "
                    extraPlugins: 'bbcode,autogrow',
                    disableObjectResizing: true,
                    autoGrow_onStartup: true,
    	            autoGrow_maxHeight: 800,
                    autoGrow_minHeight: 300,
                    removePlugins: 'resize,bidi,dialogadvtab,div,flash,format,forms,horizontalrule,iframe,justify,liststyle,pagebreak,showborders,stylescombo,table,tabletools,templates',
                    fontSize_sizes: '10/10px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;29/29px',
                    toolbar: [
                        [ 'Source' ],
                        [ 'Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo' ],
                        [ 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat' ],
                        '/',
                        [ 'Bold', 'Italic', 'Underline' ],
                        [ 'FontSize' ],
                        [ 'TextColor' ],
                        [ 'NumberedList', 'BulletedList', '-', 'Blockquote' ],
                        [ 'Link', 'Unlink', 'Image' ],
                        [ 'Maximize' ]
                    ]
                ";
                break;
        }
        
        return $config;
    }
}