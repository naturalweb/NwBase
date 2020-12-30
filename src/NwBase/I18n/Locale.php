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
        'ES' => array (
            'A'  =>  'Alicante',
            'AB' =>  'Albacete',
            'AL' =>  'Almería',
            'AV' => 'Ávila',
            'B'  => 'Barcelona',
            'BA' =>  'Badajoz',
            'BI' =>  'Bilbao',
            'BU' =>  'Burgos',
            'C'  =>  'Coruña',
            'CA' =>  'Cádiz',
            'CC' =>  'Cáceres',
            'CE' =>  'Ceuta',
            'CO' =>  'Córdoba',
            'CR' =>  'Ciudad Real',
            'CS' =>  'Castellón',
            'CU' =>  'Cuenca',
            'GC' => 'Las Palmas de Gran Canaria',
            'GE' => 'Gerona',
            'GR' =>  'Granada',
            'GU' =>  'Guadalajara',
            'H'  =>  'Huelva',
            'HU' => 'Huesca',
            'J'  =>  'Jaén',
            'L'  =>  'Lérida',
            'LE' =>  'León',
            'LO' =>  'Logroño',
            'LU' =>  'Lugo',
            'M'  =>  'Madrid',
            'MA' =>  'Málaga',
            'ML' =>  'Melilla',
            'MU' =>  'Murcia',
            'NA' => 'Navarra',
            'O'  =>  'Oviedo',
            'OR' =>  'Orense',
            'P'  =>  'Palencia',
            'PM' =>  'Palma de Mallorca',
            'PO' =>  'Pontevedra',
            'S'  =>  'Santander',
            'SA' =>  'Salamanca',
            'SE' =>  'Sevilla',
            'SG' =>  'Segovia',
            'SO' =>  'Soria',
            'SS' => 'San Sebastián',
            'T'  =>  'Tarragona',
            'TE' =>  'Teruel',
            'TF' =>  'Santa Cruz de Tenerife',
            'TO' =>  'Toledo',
            'V'  =>  'Valencia',
            'VA' =>  'Valladolid',
            'VI' =>  'Vizcaya',
            'Z'  =>  'Zaragoza',
            'ZA' =>  'Zamora',
        ),
        'PT' => array (
            'AV' => 'Aveiro',
            'BE' => 'Beja',
            'BA' => 'Braga',
            'BG' => 'Bragança',
            'CA' => 'Castelo Branco',
            'CO' => 'Coimbra',
            'EV' => 'Évora',
            'FA' => 'Faro',
            'GU' => 'Guarda',
            'LE' => 'Leiria',
            'LI' => 'Lisboa',
            'PO' => 'Portalegre',
            'PO' => 'Porto',
            'SA' => 'Santarém',
            'SE' => 'Setúbal',
            'VA' => 'Viana do Castelo',
            'VL' => 'Vila Real',
            'VS' => 'Viseu',
        ),
        'CO' => array(
            'DI' => 'Distrito Capital',
            'AM' => 'Amazonas',
            'AN' => 'Antioquia',
            'AR' => 'Arauca',
            'AT' => 'Atlántico',
            'BO' => 'Bolívar',
            'BO' => 'Boyacá',
            'CA' => 'Caldas',
            'CA' => 'Caquetá',
            'CA' => 'Casanare',
            'CA' => 'Cauca',
            'CE' => 'Cesar',
            'CH' => 'Chocó',
            'CO' => 'Córdoba',
            'CU' => 'Cundinamarca',
            'GU' => 'Guainía',
            'GU' => 'Guaviare',
            'HU' => 'Huila',
            'LA' => 'La Guajira (departamento)',
            'MA' => 'Magdalena',
            'ME' => 'Meta',
            'NA' => 'Nariño',
            'NO' => 'Norte de Santander',
            'PU' => 'Putumayo',
            'QU' => 'Quindío',
            'RI' => 'Risaralda',
            'SA' => 'San Andrés e Providencia',
            'SA' => 'Santander',
            'SU' => 'Sucre',
            'TO' => 'Tolima',
            'VA' => 'Valle del Cauca',
            'VA' => 'Vaupés',
            'VI' => 'Vichada',
        )
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

    public static function getLocaleByCliente()
    {
        return array(
            'spotmx'        => 'es_mx',
            'bferrazmx'     => 'es_mx',
            'devia'         => 'es_es',
            'vickyfoods'    => 'es_es',
            'grefusa'       => 'es_es',
            'beces'         => 'es_es',
            'equanto'       => 'pt_pt',
            'hkt'           => 'es_co',
            'boiron'        => 'es_co',
            'tmcspain'      => 'es_es',
            'heladosnestle' => 'es_es',
            'tmcmx'         => 'es_mx',
            'palmira'       => 'es_co',
            'sigmamx'       => 'es_mx',
        );
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
        $localeByClient = self::getLocaleByCliente();

        if (array_key_exists(CLIENT_NAME, $localeByClient)) {
            $locale = $localeByClient[CLIENT_NAME];
        }
    }
}
