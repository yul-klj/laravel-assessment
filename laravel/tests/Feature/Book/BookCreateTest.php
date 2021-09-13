<?php

namespace Tests\Feature;

use Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature Test for Create book
 *
 * @package Tests\Feature
 * @author Yul <yul_klj@hotmail.com>
 */
class BookCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * This will run before every test
     */
    protected function setUp():void
    {
        parent::setUp();
    }

    /**
     * This will run after every test
     */
    protected function tearDown():void
    {
        parent::tearDown();
    }

    /**
     * Test Book Create with validation error
     *
     * @return void
     */
    public function testCreateApiRequestFail()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/book', []);

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
     * Test Book Create successfully
     *
     * @return void
     */
    public function testCreateApiRequestSuccess()
    {
        $faker = Faker\Factory::create();
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/book', [
                'title' => $faker->name(),
                'author' => $faker->name()
            ]);

        $response
            ->assertStatus(200);
    }
}
