<?php

namespace Tests\Feature;

use Faker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Export;
use App\Services\ExportService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Filesystem\Filesystem;

/**
 * Unit Test for Export core features with validations
 *
 * @package Tests\Unit
 * @author Yul <yul_klj@hotmail.com>
 */
class ExportUnitTest extends TestCase
{
    use RefreshDatabase;


    /**
     * This will run before every test
     */
    protected function setUp():void
    {
        parent::setUp();
        $this->book = Book::factory()->create();
        $this->ExportService = app(ExportService::class);
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
     * This is to test CSV export core feature without Issue generating
     *
     * @return void
     */
    public function testExportCsvSuccess()
    {
        $exportFactory = Export::factory()->create([
            'type' => 'CSV'
        ]);
        $this->ExportService->csvExport($exportFactory->id);

        $exportDetail = Export::find(1);
        $explodedLocationUrl = explode('/', $exportDetail->location);
        $fileName = $explodedLocationUrl[count($explodedLocationUrl) - 1];
        Storage::assertExists('/public/' . $fileName);
    }

    /**
     * This is to test CSV export core feature failed if empty string field found
     *
     * @return void
     */
    public function testExportCsvWithEmptyStringFieldFail()
    {
        $exportFactory = Export::factory()->create([
            'type' => 'CSV',
            'fields' => ''
        ]);

        try {
            $this->ExportService->csvExport($exportFactory->id);
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getMessage(), 'Unknown fields being selected.');
        }
    }

    /**
     * This is to test XML export core feature without Issue generating
     *
     * @return void
     */
    public function testExportInitializeXmlApiRequestSuccess()
    {
        $exportFactory = Export::factory()->create([
            'id' => 2,
            'type' => 'XML'
        ]);

        $this->ExportService->xmlExport($exportFactory->id);

        $exportDetail = Export::find(2);
        $explodedLocationUrl = explode('/', $exportDetail->location);
        $fileName = $explodedLocationUrl[count($explodedLocationUrl) - 1];
        Storage::assertExists('/public/' . $fileName);
    }

    /**
     * This is to test CSV export core feature failed if unknown type found
     *
     * @return void
     */
    public function testExportXmlWithUnknownTypeFail()
    {
        $exportFactory = Export::factory()->create([
            'type' => 'XLSX'
        ]);

        try {
            $this->ExportService->xmlExport($exportFactory->id);
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getMessage(), 'Unknown type being selected.');
        }
    }
}
