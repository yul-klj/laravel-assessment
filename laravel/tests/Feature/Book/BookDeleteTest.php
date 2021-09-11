<?php

namespace Tests\Feature;

use Faker;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This will run before every test
     */
    protected function setUp():void
    {
        parent::setUp();
        $this->book = Book::factory()->create();
    }

    /**
     * This will run after every test
     */
    protected function tearDown():void
    {
        parent::tearDown();
    }

    /**
     * Test Book Delete with validation error
     *
     * @return void
     */
    public function testDeleteApiRequestFail()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->delete('/api/v1/book/2');

        $response
            ->assertStatus(404);
    }

    /**
     * Test Book Delete successfully
     *
     * @return void
     */
    public function testDeleteApiRequestSuccess()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->delete('/api/v1/book/1');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data', [])
            ->assertJsonPath('content.message', 'Accomplished');
    }
}
