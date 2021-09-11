<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookRetrieveTest extends TestCase
{
    use RefreshDatabase;

    /** @var Book $book */
    private $book;

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
     * Test Books retrival success
     *
     * @return void
     */
    public function testRetrieveAllApiRequestSuccess()
    {
        $response = $this->get('/api/v1/books');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.data.0.id', 1)
            ->assertJsonPath('content.data.data.0.author', 'Yul');
    }

    /**
     * Test Books search success
     *
     * @return void
     */
    public function testSearchBookApiRequestSuccess()
    {
        $response = $this->get('/api/v1/books?keyword=Yul');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.data.0.id', 1)
            ->assertJsonPath('content.data.data.0.author', 'Yul');
    }

    /**
     * Test Books search no result success
     *
     * @return void
     */
    public function testSearchBookApiRequestEmpty()
    {
        $response = $this->get('/api/v1/books/search?keyword=abc');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.data', []);
    }
}
