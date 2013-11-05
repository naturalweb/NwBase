<?php

namespace NwBaseTest\I18n;

use NwBase\I18n\Locale;

class LocaleTest extends \PHPUnit_Framework_TestCase
{
    public function testNomeUF()
    {
        $expected = array(
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
        );
        
        $actual = Locale::nomesUF('pt_BR');
        $this->assertEquals($expected, $actual);
    }
    
    public function testSiglasUF()
    {
        $expected = array(
            'AC' => 'AC', 'AL' => 'AL',
            'AP' => 'AP', 'AM' => 'AM',
            'BA' => 'BA', 'CE' => 'CE',
            'DF' => 'DF', 'ES' => 'ES',
            'GO' => 'GO', 'MA' => 'MA',
            'MT' => 'MT', 'MS' => 'MS',
            'MG' => 'MG', 'PA' => 'PA',
            'PB' => 'PB', 'PR' => 'PR',
            'PE' => 'PE', 'PI' => 'PI',
            'RJ' => 'RJ', 'RN' => 'RN',
            'RS' => 'RS', 'RO' => 'RO',
            'RR' => 'RR', 'SC' => 'SC',
            'SP' => 'SP', 'SE' => 'SE',
            'TO' => 'TO');
        
        $actual = Locale::siglasUF('pt_BR');
        $this->assertEquals($expected, $actual);
    }
}