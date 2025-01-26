<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationFailsWithNumberInNamesBy_MikeHaf_Test  extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreValidationFails()
    {
        // Arrange: Prepare invalid data
        $invalidData = [
            'first_name' => '', // Missing first name
            'last_name' => '', // Missing last name
            'email' => 'invalid-email', // Invalid email format
            'amount' => 0, // Invalid amount (less than 1)
        ];

        // Act: Send a POST request to the store method
        $response = $this->post('/pay', $invalidData);

        // Assert: Check for validation errors
        $response->assertRedirect('/pay'); // Expect to be redirected back
        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'amount']); // Check for specific error messages
    }
}
