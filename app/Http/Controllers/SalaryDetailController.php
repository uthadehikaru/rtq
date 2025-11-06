<?php

namespace App\Http\Controllers;

use App\Services\SalaryService;
use Illuminate\Http\Request;

class SalaryDetailController extends Controller
{
    public function index($salary_id)
    {
        $data['title'] = __('Details');
        $salary = (new SalaryService())->findDetails($salary_id);
        $details = $salary->details;
        $data['salary'] = $salary;
        $data['details'] = $details;
        $data['total'] = $details->sum('amount');

        return view('datatables.salary-details', $data);
    }

    public function create($salary_id)
    {
        //
    }

    public function store(Request $request, $salary_id)
    {
        //
    }

    public function edit($salary_id, $id)
    {
        $detail = (new SalaryService())->findDetail($id);
        $data['title'] = __('Edit Bisyaroh '.$detail->user->name. ' - '.$detail->salary->name);
        $data['detail'] = $detail;

        return view('forms.salary-detail', $data);
    }

    public function update(Request $request, $salary_id, $id)
    {
        $detail = (new SalaryService())->findDetail($id);
        $summary = $detail->summary;
        
        // Update course type amounts
        $total = 0;
        foreach (['tahsin_anak', 'tahsin_dewasa', 'tahsin_balita', 'talaqqi_jamai'] as $type) {
            if (isset($request->$type['amount'])) {
                $summary[$type]['amount'] = $request->$type['amount'];
                $total += $request->$type['amount'];
            }
        }
        
        $summary['base'] = $total;

        if ($request->has('transportasi')) {
            $summary['transportasi'] = $request->transportasi;
            $total += $request->transportasi;
        }
        if ($request->has('potongan_telat')) {
            $summary['potongan_telat'] = $request->potongan_telat;
            $total -= $request->potongan_telat;
        }
        if ($request->has('nominal_oper')) {
            $summary['nominal_oper'] = $request->nominal_oper;
            $total += $request->nominal_oper;
        }
        if ($request->has('tunjangan')) {
            $summary['tunjangan'] = $request->tunjangan;
            $total += $request->tunjangan;
        }
        
        $detail->summary = $summary;
        $detail->amount = $total;
        $detail->save();

        return redirect()->route('salaries.details.index', $detail->salary_id)->with('message', 'Updated');
    }

    public function destroy($salary_id, $id)
    {
        (new SalaryService())->deleteDetail($id);

        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
