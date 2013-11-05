<?php
/**
 * Natural Web Ltda. (http://www.naturalweb.com.br)
 *
 * @copyright 2013 - Copyright (c) Natural Web Ltda. (http://www.naturalweb.com.br)
 * @license   BSD-3-Clause http://opensource.org/licenses/BSD-3-Clause
 */
namespace NwBase\I18n;

/**
 * Classe com conjunto de funções sobre a localização do projeto
 *
 * @category NwBase
 * @package  NwBase\I18n
 * @author   Renato Moura <renato@naturalweb.com.br>
 */
class Locale extends \Locale
{
    /**
     * Lista de UFs
     * @var array
     */
    protected static $_listUF = array(
        'BR' => array(
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ),
    );
    
    /**
     * Retorna a lista de UF (Estados) do pais,
     * com a sigla de chave e nome de valor
     *
     * @return array
     */
    public static function nomesUF($locale = null)
    {
        $list = array();
        if (!$locale) {
            $locale = self::getDefault();
        }
        
        $region = self::getRegion($locale);
        if (isset(self::$_listUF[$region])) {
            $list = self::$_listUF[$region];
        }
        
        return $list;
    }
    
    /**
     * Retorna a lista de UF (Estados) do pais,
     * com a sigla de chave e valor
     *
     * @return array
     */
    public static function siglasUF($locale = null)
    {
        $nomes = self::nomesUF($locale);
        $siglas = array_keys($nomes);
        $list = array_combine($siglas, $siglas);
        return $list;
    }
}
