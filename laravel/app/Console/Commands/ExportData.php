<?php

namespace App\Console\Commands;

use App\Models\Export;
use App\Services\ExportService;
use Illuminate\Console\Command;

/**
 * Class ExportData
 *
 * This function is to generate csv or xml in backend, and updated the download link accordingly
 *
 * @package App\Http\Controllers
 * @author  Yul <yul_klj@hotmail.com>
 */
class ExportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export {export_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is to export in backend, export id is the needle for generate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! empty($this->argument('export_id'))) {
            $exportLog = app(ExportService::class)->getExportDetail(
                $this->argument('export_id')
            );
            if (! empty($exportLog)) {
                if ($exportLog['type'] === Export::CSV_EXPORT_TYPE) {
                    app(ExportService::class)->csvExport($exportLog['id']);
                } else {
                    app(ExportService::class)->xmlExport($exportLog['id']);
                }
                return;
            }
        }
        return;
    }
}
