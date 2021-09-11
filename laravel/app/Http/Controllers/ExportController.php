<?php

namespace App\Http\Controllers;

use App\Models\Export;
use App\Services\ExportService;
use App\Http\Requests\ExportUrlRequest;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\ExportGenerateRequest;

/**
 * Class ExportController
 *
 * @package App\Http\Controllers
 * @author  Yul <yul_klj@hotmail.com>
 */
class ExportController extends Controller
{
    /**
     * Initialize export function
     *
     * Once this being initialized and it will trigger backend to export
     *
     * @param ExportGenerateRequest $request Export generate request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function initializeExport(ExportGenerateRequest $request)
    {
        $data['type'] = $request->input('type', Export::CSV_EXPORT_TYPE);
        $data['field'] = $request->input('field', '');

        $exportLog = app(ExportService::class)->generateExportEntry($data);
        $exportId = $exportLog['id'];
        // This is to call the export function to be done in backend
        Artisan::call("export $exportId");

        return $this->respondAccomplished('Export initialized', $exportLog);
    }

    /**
     * Retrieve export detail
     *
     * This is to recursively check if data is being exported
     * if yes, the location link updated, file could be download
     *
     * @param int              $id      Export id to retrieve
     * @param ExportUrlRequest $request Export url request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function retrieveExport(int $id, ExportUrlRequest $request)
    {
        $exportLog = app(ExportService::class)->getExportDetail($id);

        return $this->respondAccomplished('Export detail retrieved', $exportLog);
    }
}
