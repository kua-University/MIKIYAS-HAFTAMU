<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CallbackPaymentFailureUTBy_MikeHaf_Test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCallbackPaymentFailure()
    {
        // Arrange: Create a payment record in the database
        $payment = Payment::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
            'tx_ref' => 'test_reference',
            'amount' => 100,
            'status' => 'pending', // Initial status
        ]);
    
        // Mock transaction verification to return failure
        Chapa::shouldReceive('verifyTransaction')
            ->once()
            ->with('test_reference')
            ->andReturn(['status' => 'fail']);
    
        // Act: Send a GET request to the callback method
        $response = $this->get('/callback/test_reference');
    
        // Assert: Check payment status remains unchanged and redirected with error message
        $this->assertDatabaseHas('payments', [
            'tx_ref' => 'test_reference',
            'status' => 'pending', // Should still be pending
        ]);
    
        $response->assertRedirect('/');
        $response->assertSessionHas('error', 'Payment is not Sucessful');
    }
}
