<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    /**
     * Test greater than validator
     *
     * @return void
     */
    public function testGreaterThan()
    {
        $validator = Validator::make([
            'value' => 0
        ], [
            'value' => 'greater_than:0'
        ]);

        $this->assertTrue($validator->fails());

        $validator = Validator::make([
            'value' => 1
        ], [
            'value' => 'greater_than:0'
        ]);

        $this->assertFalse($validator->fails());
    }
}
