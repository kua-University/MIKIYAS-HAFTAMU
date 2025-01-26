<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Controllers\PaymentController;
use Chapa\Chapa\Facades\Chapa;

class GenerateReferenceUTBy_MikeHaf_Test  extends TestCase
{
    /**
     * Test that the reference is generated correctly.
     *
     * @return void
     */
    public function testConstructorGeneratesReference()
    {
        // Mock the Chapa facade to return a specific reference
        $mockReference = 'test_reference_123';
        Chapa::shouldReceive('generateReference')
            ->once()
            ->andReturn($mockReference);
    
        // Create an instance of PaymentController
        $controller = new PaymentController();
    
        // Assert that the reference is set correctly using the getter method
        $this->assertEquals($mockReference, $controller->getReference());
    }
}