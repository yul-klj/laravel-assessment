<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature Test for Update book
 *
 * @package Tests\Feature
 * @author Yul <yul_klj@hotmail.com>
 */
class BookUpdateTest extends TestCase
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
     * Test Book Update with validation error
     *
     * @return void
     */
    public function testUpdateApiRequestFail()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->putJson('/api/v1/book/1', []);

        $response
            ->assertStatus(422)
            ->assertJson([
                "success" => "false",
                "code" => "UNPROCESSABLE_ENTITY",
                "http_code" => 422,
                "content" => [
                    "error" => [
                        "title" => [
                            "The title field is required."
                        ],
                        "author" => [
                            "The author field is required."
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test Book Update successfully
     *
     * @return void
     */
    public function testUpdateApiRequestSuccess()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->putJson('/api/v1/book/1', [
                'title' => 'Once upon a time',
                'author' => 'Yul Kok'
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.id', 1)
            ->assertJsonPath('content.data.title', 'Once upon a time')
            ->assertJsonPath('content.data.author', 'Yul Kok');
    }
}
