<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CallbackPaymentSuccessUTBy_MikeHaf_Test  extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCallbackPaymentSuccess()
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
    
        // Mock transaction verification to return success
        Chapa::shouldReceive('verifyTransaction')
            ->once()
            ->with('test_reference')
            ->andReturn(['status' => 'success']);
    
        // Act: Send a GET request to the callback method
        $response = $this->get('/callback/test_reference');
    
        // Assert: Check payment status updated and redirected with success message
        $this->assertDatabaseHas('payments', [
            'tx_ref' => 'test_reference',
            'status' => 'success',
        ]);
        
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Payment is Sucessful');
    }
}
