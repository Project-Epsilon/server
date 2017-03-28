<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;

class ValidationTest extends TestCase
{
    /**
     * A greater than validation rule test.
     *
     * @return void
     */
    public function testGreaterThanRule()
    {
        $validator = Validator::make([
            'value' => 0
        ], [
            'value' => 'required|greater_than:0'
        ]);

        $this->assertTrue($validator->fails());

        $validator = Validator::make([
            'value' => 1
        ], [
            'value' => 'required|greater_than:0'
        ]);

        $this->assertFalse($validator->fails());
    }
}
