<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Payment;
use Chapa\Chapa\Facades\Chapa;
use Illuminate\Support\Facades\Log;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_initialize_payment_and_handle_successful_callback()
    {
        // Mock the Chapa facade to simulate a successful payment initialization
        Chapa::shouldReceive('generateReference')->andReturn('test_ref_123');
        Chapa::shouldReceive('initializePayment')->andReturn([
            'status' => 'success',
            'data' => [
                'checkout_url' => 'https://chapa.example.com/checkout'
            ]
        ]);

        // Mock the Chapa facade to simulate a successful payment verification
        Chapa::shouldReceive('verifyTransaction')->andReturn([
            'status' => 'success'
        ]);

        // Simulate a POST request to the payment store endpoint
        $response = $this->post('/pay', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'amount' => 1000
        ]);

        // Assert that the response redirects to the checkout URL
        $response->assertRedirect('https://chapa.example.com/checkout');

        // Simulate a GET request to the callback endpoint
        $callbackResponse = $this->get(route('callback', ['test_ref_123']));

        // Assert that the payment status is updated to 'success'
        $this->assertDatabaseHas('payments', [
            'tx_ref' => 'test_ref_123',
            'status' => 'success'
        ]);

        // Assert that the user is redirected with a success message
        $callbackResponse->assertRedirect('/')->with('success', 'Payment is Sucessful');
    }

    /** @test */
    public function it_handles_failed_payment_initialization()
    {
        // Mock the Chapa facade to simulate a failed payment initialization
        Chapa::shouldReceive('generateReference')->andReturn('test_ref_123');
        Chapa::shouldReceive('initializePayment')->andReturn([
            'status' => 'fail'
        ]);

        // Simulate a POST request to the payment store endpoint
        $response = $this->post('/pay', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'amount' => 1000
        ]);

        // Assert that the response redirects to the home page with an error message
        $response->assertRedirect('/')->with('error', 'Sorry, payment was not successful');

        // Assert that the payment status is updated to 'fail'
        $this->assertDatabaseHas('payments', [
            'tx_ref' => 'test_ref_123',
            'status' => 'fail'
        ]);
    }

    /** @test */
    public function it_handles_failed_payment_callback()
    {
        // Mock the Chapa facade to simulate a failed payment verification
        Chapa::shouldReceive('verifyTransaction')->andReturn([
            'status' => 'fail'
        ]);

        // Create a payment record in the database
        $payment = Payment::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'tx_ref' => 'test_ref_123',
            'amount' => 1000,
            'status' => 'pending'
        ]);

        // Simulate a GET request to the callback endpoint
        $callbackResponse = $this->get(route('callback', ['test_ref_123']));

        // Assert that the payment status remains 'pending' or is updated to 'fail'
        $this->assertDatabaseHas('payments', [
            'tx_ref' => 'test_ref_123',
            'status' => 'pending' // or 'fail' depending on your logic
        ]);

        // Assert that the user is redirected with an error message
        $callbackResponse->assertRedirect('/')->with('error', 'Payment is not Sucessful');
    }
}