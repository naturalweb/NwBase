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
        'MX' => array(
            'AG' => 'Aguascalientes',
            'BC' => 'Baja California',
            'BS' => 'Baja California Sur',
            'CM' => 'Campeche',
            'CS' => 'Chiapas',
            'CH' => 'Chihuahua',
            'CO' => 'Coahuila',
            'CL' => 'Colima',
            'CX' => 'Ciudad de México',
            'DG' => 'Durango',
            'GT' => 'Guanajuato',
            'GR' => 'Guerrero',
            'HG' => 'Hidalgo',
            'JC' => 'Jalisco',
            'ME' => 'Estado de México',
            'MN' => 'Michoacán',
            'MS' => 'Morelos',
            'NT' => 'Nayarit',
            'NL' => 'Nuevo León',
            'OC' => 'Oaxaca',
            'PL' => 'Puebla',
            'QO' => 'Querétaro',
            'QR' => 'Quintana Roo',
            'SP' => 'San Luis Potosí',
            'SL' => 'Sinaloa',
            'SR' => 'Sonora',
            'TC' => 'Tabasco',
            'TS' => 'Tamaulipas',
            'TL' => 'Tlaxcala',
            'VZ' => 'Veracruz',
            'YN' => 'Yucatán',
            'ZS' => 'Zacatecas',
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

        self::overwriteLocale($locale);

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
        self::overwriteLocale($locale);
        $nomes = self::nomesUF($locale);
        $siglas = array_keys($nomes);
        $list = array_combine($siglas, $nomes);
        return $list;
    }

    /**
     * Sobrescreve $locale se for PMR SPOTMX
     *
     * @param  string $locale
     *
     * @return string
     */
    private static function overwriteLocale(&$locale)
    {
        // Altera locale para exibir UFs do México em spotmx
        if (defined('CLIENT_NAME') && CLIENT_NAME == 'spotmx') {
            $locale = 'es_mx';
        }
    }
}
