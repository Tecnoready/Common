<?php

namespace Tecnoready\Common\Tests\Service\PaymentGateway;

use PHPUnit\Framework\TestCase;
use Tecnoready\Common\Service\PaymentGateway\Instapago;

/**
 * Test de pasarela de instapago
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class InstapagoTest extends TestCase
{
    /**
     * @return Instapago
     */
    private function getNullInstapago() {
        return new Instapago("invalidinvalidinvalid","invalidinvalidinvalid");
    }
    /**
     * Instancia valida de instapago
     * @return Instapago
     */
    private function getValidInstapago() {
        return new Instapago("51ba4e6af41154013149061a1ea7885c","C933E73A-F050-4F84-911E-AC6EB2F9405E");
    }
    /**
     * Prueba que pida los parametros obligatorios
     */
    public function testEmptyParameters() {
        $instapago = $this->getNullInstapago();
        $this->expectException(\Symfony\Component\OptionsResolver\Exception\MissingOptionsException::class);
        $instapago->authorization([]);
    }
    /**
     * Prueba que los parametros se normalicen
     */
    public function testParameters() {
        $instapago = $this->getNullInstapago();
        $options = $this->getOptions1();
        $response = $instapago->authorization($options);
        $this->assertFalse($response->isSuccess());
    }
    
    public function testSuccess() {
        $instapago = $this->getValidInstapago();
        $options = $this->getOptions2();
        $response = $instapago->authorization($options);
        $this->assertTrue($response->isSuccess());
    }
    
    private function getOptions1() {
        return [
            "Amount" => "2.000,59",
            "Description" => "Test de instapago",
            "CardHolder" => "Cárlos E MendózÁ B",
            "CardHolderId" => "19029341",
            "CardNumber" => "4111-1111-1111-1111",
            "CVC" => "4212",
            "ExpirationDate" => "10/2025",//MM/YYYY
            "IP" => "127.0.0.1",
        ];
    }
    private function getOptions2() {
        return [
            "Amount" => "2.000,59",
            "Description" => "Test de instapago",
            "CardHolder" => "Cárlos E MendózÁ B",
            "CardHolderId" => "19029341",
            "CardNumber" => "4111-1111-1111-1111",
            "CVC" => "341",
            "ExpirationDate" => "10/2025",//MM/YYYY
            "IP" => "127.0.0.1",
        ];
    }
}
