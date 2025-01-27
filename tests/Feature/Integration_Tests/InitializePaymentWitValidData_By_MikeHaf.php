<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Payment;
use Chapa\Chapa\Facades\Chapa;


class InitializePaymentWitValidData_By_MikeHaf extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_initialize_a_payment_with_valid_data()
    {
        // Mock the Chapa payment initialization
        Chapa::shouldReceive('initializePayment')
            ->once()
            ->andReturn(['status' => 'success', 'data' => ['checkout_url' => 'http://example.com/checkout']]);
    
        $response = $this->post('/pay', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'amount' => 5000,
        ]);
    
        $response->assertRedirect('http://example.com/checkout'); // Check redirect to checkout URL
    }
}