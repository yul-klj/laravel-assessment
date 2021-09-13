<?php

namespace Tests\Feature;

use Faker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Export;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature Test for Export
 *
 * @package Tests\Feature
 * @author Yul <yul_klj@hotmail.com>
 */
class ExportTest extends TestCase
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
        $file = new Filesystem;
        $file->cleanDirectory('storage/app/public');
    }

    /**
     * Test Book Export CSV
     *
     * @return void
     */
    public function testExportInitializeCsvApiRequestSuccess()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=CSV');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.type', 'CSV')
            ->assertJsonPath('content.data.fields', 'title,author')
            ->assertJsonPath('content.message', 'Export initialized');
    }

    /**
     * Test Book Export XML
     *
     * @return void
     */
    public function testExportInitializeXmlApiRequestSuccess()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=XML');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.type', 'XML')
            ->assertJsonPath('content.data.fields', 'title,author')
            ->assertJsonPath('content.message', 'Export initialized');
    }

    /**
     * Test Book Export CSV
     *
     * @return void
     */
    public function testExportCsvFilePathApiRequestSuccess()
    {
        $initializeResponse = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=CSV');

        $initializeResponseArr = $initializeResponse->json('content');

        $export = Export::first();

        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->get('/api/v1/export/' . $initializeResponseArr['data']['id']);

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.type', 'CSV')
            ->assertJsonPath('content.data.location', $export->location);
    }

    /**
     * Test Book Export XML
     *
     * @return void
     */
    public function testExportXmlFilePathApiRequestSuccess()
    {
        $initializeResponse = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=XML');

        $initializeResponseArr = $initializeResponse->json('content');

        $export = Export::first();

        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->get('/api/v1/export/' . $initializeResponseArr['data']['id']);

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.type', 'XML')
            ->assertJsonPath('content.data.location', $export->location);
    }

    /**
     * Test Book Export with non csv, xml
     *
     * @return void
     */
    public function testExportFilePathApiRequestFail()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=XLXS');

        $response
            ->assertStatus(422)
            ->assertJsonPath('content.error.type.0', 'The selected type is invalid.');
    }

    /**
     * Test Book Export with csv, specific field
     *
     * @return void
     */
    public function testExportCsvFilePathSpecificFieldApiRequestSuccess()
    {
        $response = $this->withHeaders(['Content-Type' => 'application/json'])
            ->postJson('/api/v1/export?type=CSV&field=title');

        $response
            ->assertStatus(200)
            ->assertJsonPath('content.data.type', 'CSV')
            ->assertJsonPath('content.data.fields', 'title')
            ->assertJsonPath('content.message', 'Export initialized');
    }
}
