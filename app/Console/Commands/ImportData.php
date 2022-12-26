<?php

namespace App\Console\Commands;

use App\Imports\RTQImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rtq:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Data RTQ';

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
        if (! Storage::disk('local')->exists('import.xlsx')) {
            return $this->info('No File Import Exist');
        }

        Excel::import(new RTQImport, storage_path('app/import.xlsx'));
        $this->info('Imported');
    }
}
