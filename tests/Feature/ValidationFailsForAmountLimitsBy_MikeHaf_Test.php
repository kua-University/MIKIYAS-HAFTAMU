<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ValidationFailsForAmountLimitsBy_MikeHaf_Test  extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreValidationFailsForAmountlessthan_one()
    {
        // Arrange: Prepare invalid data with amount less than 1
        $dataLessThanOne = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'amount' => 0, // Invalid amount (less than 1)
        ];
    
        // Act: Send a POST request to the store method
        $response = $this->post('/pay', $dataLessThanOne);
    
        // Assert: Check for validation errors
        $response->assertRedirect('/pay');
        $response->assertSessionHasErrors(['amount']); // Check for specific error messages
    }

    public function testStoreValidationFailsForAmountLimitsgthan_teenk(){

        // Arrange: Prepare invalid data with amount greater than 10,000
        $dataGreaterThanTenThousand = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'amount' => 10001, // Invalid amount (greater than 10,000)
        ];
    
        // Act: Send a POST request to the store method
        $response = $this->post('/pay', $dataGreaterThanTenThousand);
    
        // Assert: Check for validation errors
        $response->assertRedirect('/pay');
        $response->assertSessionHasErrors(['amount']); // Check for specific error messages
    
    }


    }