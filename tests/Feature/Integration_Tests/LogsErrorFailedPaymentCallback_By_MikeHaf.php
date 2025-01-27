<?php

namespace Tests\Feature\Integration_Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogsErrorFailedPaymentCallback_By_MikeHaf extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
   /** @test */
   public function it_logs_error_on_failed_payment_callback()
   {
       // Create a payment record
       $payment = Payment::create([
           'first_name' => 'John',
           'last_name' => 'Doe',
           'email' => 'john@example.com',
           'tx_ref' => 'test_reference',
           'amount' => 5000,
           'status' => 'pending',
       ]);

       // Mock the Chapa transaction verification
       Chapa::shouldReceive('verifyTransaction')
           ->once()
           ->with('test_reference')
           ->andReturn(['status' => 'fail']);

       \Log::shouldReceive('error')->once(); // Expect error logging

       $response = $this->get('/callback/test_reference');

       $response->assertRedirect('/');
       $response->assertSessionHas('error', 'Payment is not Sucessful');
       $this->assertDatabaseHas('payments', [
           'tx_ref' => 'test_reference',
           'status' => 'pending', // Status should remain pending
       ]);
   }
}
