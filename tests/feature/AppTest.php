<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AppTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A get currencies tests.
     *
     * @return void
     */
    public function testGetCurrency()
    {
        $this->seed();

        $this->get('api/app/currencies')
            ->assertJsonStructure([
            'data' => ['*' => [
                'code', 'name', 'minor_unit'
            ]]
        ]);
    }

    public function testCallback()
    {
        $this->get('api/app/callback')
            ->assertSee('Success');
    }

}
