<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature Test for Retrieve book
 *
 * @package Tests\Feature
 * @author Yul <yul_klj@hotmail.com>
 */
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
     * Test Books retrival success, this api checks without paginations
     * It will returns all the data in Database
     *
     * @return void
     */
    public function testRetrieveAllApiRequestSuccess()
    {
        $response = $this->get('/api/v1/books');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.0.id', 1)
            ->assertJsonPath('content.data.0.author', 'Yul');
    }

    /**
     * Test Books search success
     * Expect search result, comparing returned value
     *
     * @return void
     */
    public function testSearchBookApiRequestSuccess()
    {
        $response = $this->get('/api/v1/books/search?keyword=Yul');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.data.0.id', 1)
            ->assertJsonPath('content.data.data.0.author', 'Yul');
    }

    /**
     * Test Books search no result success
     * Expecting empty array returned
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
