<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */

return array(
    'view_helpers' => array(
        'invokables' => array(
            'rowsEmpty'       => 'NwBase\View\Helper\RowsEmpty',
            'htmlTable'       => 'NwBase\View\Helper\htmlTable',
            'mask'            => 'NwBase\View\Helper\Mask',
            'maskCep'         => 'NwBase\View\Helper\MaskCep',
            'maskCnpj'        => 'NwBase\View\Helper\MaskCnpj',
            'maskCpf'         => 'NwBase\View\Helper\MaskCpf',
            'maskPhone'       => 'NwBase\View\Helper\MaskPhone',
            'truncateString'  => 'NwBase\View\Helper\Truncate',
            'markdown'        => 'NwBase\View\Helper\Markdown',
            'bbcode'          => 'NwBase\View\Helper\BBCode',
            'formCkeditor'    => 'NwBase\Form\View\Helper\FormCkeditor',
        ),
    ),
);