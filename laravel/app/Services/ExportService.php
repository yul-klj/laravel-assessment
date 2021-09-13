<?php

namespace App\Services;

use Exception;
use Spatie\ArrayToXml\ArrayToXml;
use App\Repositories\BookRepository;
use App\Repositories\ExportRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Class ExportService
 *
 * @package App\Services
 * @author Yul <yul_klj@hotmail.com>
 */
class ExportService
{
    private $bookRepository;
    private $exportRepository;

    /**
     * ExportService constructor.
     *
     * @param BookRepository   $bookRepository   book repository
     * @param ExportRepository $exportRepository export repository
     */
    public function __construct(
        BookRepository $bookRepository,
        ExportRepository $exportRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->exportRepository = $exportRepository;
    }

    /**
     * Create Export Entry
     *
     * @param array $data Validated data to be inserted
     * @return array
     */
    public function generateExportEntry(array $data)
    {
        $hasField = $this->bookRepository->validDatabaseField($data['field']);

        if (! $hasField) {
            $fields = $this->bookRepository->getAllRelatedDbField();
            $data['fields'] = implode(',', $fields);
        } else {
            $data['fields'] = $data['field'];
        }
        unset($data['field']);
        $exportDetail = $this->exportRepository->create($data);

        return $exportDetail->toArray();
    }

    /**
     * Get Export detail
     *
     * @param int $id Export id to retrieve
     * @return array
     */
    public function getExportDetail(int $id)
    {
        $exportDetail = $this->exportRepository->getById($id);
        if (empty($exportDetail)) {
            return null;
        }

        return $exportDetail->toArray();
    }

    /**
     * Update Export detail
     *
     * @param int   $id   Export id to update
     * @param array $data Validated data to be inserted
     * @return array
     */
    public function updateExportDetail(int $id, array $data)
    {
        $exportDetail = $this->exportRepository->getById($id);
        if (empty($exportDetail)) {
            return null;
        }
        $exportDetail = $this->exportRepository->update($exportDetail, $data);

        return $exportDetail->toArray();
    }

    /**
     * Generate csv function class
     *
     * @param int $id Export id to retrieve
     */
    public function csvExport(int $id)
    {
        [$exportDetail, $books] =
            $this->getExportArrayAndBooksObjectByExportId($id);

        $fields = explode(',', $exportDetail['fields']);

        $time = time();
        $fileName = "csv-export-$time.csv";
        $path = storage_path('/app/public/');
        $fullFilePath = $path . $fileName;

        $file = fopen($fullFilePath, 'w');
        fputcsv($file, $fields);

        foreach ($books as $book) {
            foreach ($fields as $field) {
                $row[$field] = $book->$field;
            }

            fputcsv($file, $row);
        }

        fclose($file);

        $exportDetail['location'] = asset('storage/' . $fileName);
        $this->updateExportDetail($exportDetail['id'], $exportDetail);
    }

    /**
     * Generate xml function class
     *
     * @param int $id Export id to retrieve
     */
    public function xmlExport(int $id)
    {
        [$exportDetail, $books] =
            $this->getExportArrayAndBooksObjectByExportId($id);
        $result = ArrayToXml::convert(['book' => $books->toArray()], 'books');
        $time = time();
        $fileName = "xml-export-$time.xml";
        Storage::put("public/$fileName", $result);

        $exportDetail['location'] = asset('storage/' . $fileName);
        $this->updateExportDetail($exportDetail['id'], $exportDetail);
    }

    /**
     * This function is for:-
     * - get export detail
     * - call validate function
     * - get all book object
     *
     * @param int $id Export id to retrieve
     * @return [array, Book] [$exportDetail, $bookObjects] export detail array and book data object
     * @throws \Exception
     */
    private function getExportArrayAndBooksObjectByExportId(int $id)
    {
        $exportDetail = $this->getExportDetail($id);
        $this->validateExportEntryData($exportDetail);

        $fields = explode(',', $exportDetail['fields']);
        $bookObjects = $this->bookRepository->getAllByFields($fields);

        return [$exportDetail, $bookObjects];
    }

    /**
     * This function is for:-
     * - the usage of validate export exists
     * - the usage of validate export type
     * - the usage of validate export fields
     *
     * @param array $exportDetail Export detail data passed in for validation
     * @throws \Exception
     */
    private function validateExportEntryData(array $exportDetail)
    {
        if (empty($exportDetail)) {
            throw new Exception('No export to be process.');
        }

        if (! $this->exportRepository->validExportType($exportDetail['type'])) {
            throw new Exception('Unknown type being selected.');
        }

        $fields = explode(',', $exportDetail['fields']);
        foreach ($fields as $field) {
            $validColumn = $this->bookRepository->validDatabaseField($field);
            if (! $validColumn) {
                throw new Exception('Unknown fields being selected.');
            }
        }
    }
}
