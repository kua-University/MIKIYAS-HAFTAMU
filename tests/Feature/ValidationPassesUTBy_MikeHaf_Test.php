<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationPassesUTBy_MikeHaf_Test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreValidationPasses()
    {
        // Arrange: Prepare valid data
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'amount' => 500, // Valid amount
        ];

        // Act: Send a POST request to the store method
        $response = $this->post('/pay', $data);

        // Assert: Check that we are redirected to the appropriate URL
        $response->assertRedirect(); // This assumes that the redirect will happen to the checkout URL
    }
}
