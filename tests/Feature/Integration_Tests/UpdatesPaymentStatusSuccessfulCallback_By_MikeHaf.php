<?php

namespace Tests\Feature\Integration_Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatesPaymentStatusSuccessfulCallback_By_MikeHaf extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
   /** @test */
   public function it_updates_payment_status_on_successful_callback()
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
           ->andReturn(['status' => 'success']);

       $response = $this->get('/callback/test_reference');

       $response->assertRedirect('/');
       $response->assertSessionHas('success', 'Payment is Sucessful');
       $this->assertDatabaseHas('payments', [
           'tx_ref' => 'test_reference',
           'status' => 'success',
       ]);
   }
}


