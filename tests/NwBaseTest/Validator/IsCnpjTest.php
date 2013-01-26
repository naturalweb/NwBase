<?php

namespace NwBaseTest\Validator;

use NwBase\Validator\IsCnpj;

class IsCnpjTest extends \PHPUnit_Framework_TestCase
{
    public function testCpnjValid()
    {
        $cnpj_valid = "63.563.726/0001-52";
        
        $isCnpj = new IsCnpj();
        $valid = $isCnpj->isValid($cnpj_valid);
        $this->assertTrue($valid, "Deveria passar o CNPJ " . $cnpj_valid);
    }
    
    public function testCpnjInvalid()
    {
        $cnpj_invalid = "112.223.330/001-12";
    
        $isCnpj = new IsCnpj();
        $valid = $isCnpj->isValid($cnpj_invalid);
        $this->assertFalse($valid, "Deveria não passar o CNPJ " . $cnpj_invalid);
        
        $msgs = $isCnpj->getMessages();
        $this->assertEquals($msgs[$isCnpj::INVALID], sprintf("Cnpj '%s' inválido!", $cnpj_invalid));
    }
    
    public function testVariosCnpjInvalidos()
    {
        $isCnpj = new IsCnpj();
        
        $this->assertFalse($isCnpj->isValid("12345678901234"));
        $this->assertFalse($isCnpj->isValid("0022312213"));
        $this->assertFalse($isCnpj->isValid("00.000.000/0000/00"));
    }
}
