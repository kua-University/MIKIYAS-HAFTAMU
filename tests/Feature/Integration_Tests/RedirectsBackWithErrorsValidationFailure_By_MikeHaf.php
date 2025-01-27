<?php

namespace Tests\Feature\Integration_Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectsBackWithErrorsValidationFailure_By_MikeHaf extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_back_with_errors_on_validation_failure()
    {
        $response = $this->post('/pay', [
            'first_name' => '',
            'last_name' => 'Doe',
            'email' => 'not-an-email',
            'amount' => 15000, // Exceeds max amount
        ]);

        $response->assertRedirect('/pay');
        $response->assertSessionHasErrors(['first_name', 'email', 'amount']); // Check for validation errors
    }
}