<?php

namespace App\Http\Controllers;

use App\DataTables\FailedJobsDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FailedJobsController extends Controller
{
    /**
     * Display a listing of failed jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FailedJobsDataTable $dataTable)
    {
        $data['title'] = 'Failed Jobs';

        return $dataTable->render('datatables.failed-jobs', $data);
    }

    /**
     * Retry a specific failed job.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function retry($id)
    {
        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if (!$failedJob) {
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed job not found.');
        }

        try {
            Artisan::call('queue:retry', ['id' => $failedJob->uuid]);
            
            return redirect()->route('failed-jobs.index')
                ->with('message', 'Job has been queued for retry.');
        } catch (\Exception $e) {
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed to retry job: '.$e->getMessage());
        }
    }

    /**
     * Forget (delete) a specific failed job.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forget(Request $request, $id)
    {
        $failedJob = DB::table('failed_jobs')->where('id', $id)->first();

        if (!$failedJob) {
            if ($request->ajax()) {
                return response()->json(['statusCode' => 404, 'message' => 'Failed job not found.'], 404);
            }
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed job not found.');
        }

        try {
            Artisan::call('queue:forget', ['id' => $failedJob->uuid]);
            
            if ($request->ajax()) {
                return response()->json(['statusCode' => 200, 'message' => 'Job has been forgotten.']);
            }
            
            return redirect()->route('failed-jobs.index')
                ->with('message', 'Job has been forgotten.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['statusCode' => 500, 'message' => 'Failed to forget job: '.$e->getMessage()], 500);
            }
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed to forget job: '.$e->getMessage());
        }
    }

    /**
     * Retry all failed jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function retryAll()
    {
        try {
            Artisan::call('queue:retry', ['id' => 'all']);
            
            return redirect()->route('failed-jobs.index')
                ->with('message', 'All failed jobs have been queued for retry.');
        } catch (\Exception $e) {
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed to retry all jobs: '.$e->getMessage());
        }
    }

    /**
     * Flush (delete) all failed jobs.
     *
     * @return \Illuminate\Http\Response
     */
    public function flushAll()
    {
        try {
            Artisan::call('queue:flush');
            
            return redirect()->route('failed-jobs.index')
                ->with('message', 'All failed jobs have been flushed.');
        } catch (\Exception $e) {
            return redirect()->route('failed-jobs.index')
                ->with('error', 'Failed to flush all jobs: '.$e->getMessage());
        }
    }
}

