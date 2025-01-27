<?php

namespace Tests\Feature\Integration_Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Chapa\Chapa\Facades\Chapa;
use Illuminate\Support\Facades\Http; // If you need to mock HTTP requests
use App\Models\Payment;

class HandlesInvalidPaymentReferenceCallback_By_MikeHaf extends TestCase
{
    use RefreshDatabase; // Use RefreshDatabase trait

    /** @test */
    public function it_handles_invalid_payment_reference_in_callback()
    {
        // Simulate a callback with an invalid reference
        $invalidReference = 'invalid_reference';

        // Mock the Chapa transaction verification to return a failure for the invalid reference
        Chapa::shouldReceive('verifyTransaction')
            ->once()
            ->with($invalidReference)
            ->andReturn(['status' => 'fail']);

        // Simulate the callback request
        $response = $this->get("/callback/{$invalidReference}");

        // Check that the user is redirected back to the home page
        $response->assertRedirect('/');

        // Verify the error message is set in the session
        $response->assertSessionHas('error', 'Payment is not Successful'); 

        // Check that no payment status was updated in the database
        $this->assertDatabaseMissing('payments', [
            'tx_ref' => $invalidReference,
            'status' => 'success', // Ensure no payment was marked as successful
        ]);
    }
}