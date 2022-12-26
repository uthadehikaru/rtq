<?php

namespace App\Http\Controllers;

use App\Exports\PaymentDetailsSheet;
use App\Exports\PeriodsExport;
use App\Interfaces\PeriodRepositoryInterface;
use App\Models\Member;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index(PeriodRepositoryInterface $periodRepository)
    {
        $data['title'] = __('Periods');
        $data['periods'] = Period::latest()
        ->withCount('paymentDetails')
        ->get();
        $data['total_members'] = Member::has('batches')->select('id')->count();
        $data['total'] = $data['periods']->count();

        return view('datatables.period', $data);
    }

    public function create()
    {
        $data['period'] = null;

        return view('forms.period', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Period::create($data);

        return to_route('periods.index')->with('message', 'Berhasil');
    }

    public function destroy($id)
    {
        $period = Period::find($id);
        if ($period->paymentDetails->count() > 0) {
            $data['message'] = 'Terdapat pembayaran pada periode ini';

            return response()->json($data, 500);
        }

        $period->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }

    public function export($period_id = null)
    {
        $period = Period::find($period_id);
        if ($period) {
            return (new PaymentDetailsSheet($period))->download('pembayaran per '.date('d M Y H.i').'.xlsx');
        }

        return (new PeriodsExport())->download('pembayaran per '.date('d M Y H.i').'.xlsx');
    }
}
