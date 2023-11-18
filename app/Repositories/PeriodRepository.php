<?php

namespace App\Repositories;

use App\Interfaces\PeriodRepositoryInterface;
use App\Models\Period;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PeriodRepository implements PeriodRepositoryInterface
{
    public function all()
    {
        return Period::all();
    }

    public function count()
    {
        return Period::count();
    }

    public function getLatest($limit = 10)
    {
        return Period::latest()->paginate($limit);
    }

    public function find($id)
    {
        return Period::findOrFail($id);
    }

    public function delete($id)
    {
        Period::destroy($id);
    }

    public function create(array $data)
    {
        return Period::create($data);
    }

    public function update($id, array $data)
    {
        return Period::whereId($id)->update($data);
    }

    public function PaymentPerPeriod(): Collection
    {
        return Period::oldest('start_date')
        ->take(12)
        ->where('name','<>','Registrasi')
        ->whereHas('paymentDetails')
        ->withCount(['paymentDetails as cash_count' => function ($query) {
            $query->whereRelation('payment', 'payment_method', 'amplop');
        }, 'paymentDetails as transfer_count' => function ($query) {
            $query->whereRelation('payment', 'payment_method', 'transfer');
        }])->get();
    }

    public function lastSixMonth()
    {
        return Period::oldest('start_date')
        ->where('name','<>','Registrasi')
        ->where('start_date','>=',Carbon::now()->subMonths(6)->startOfMonth())
        ->get();
    }
}
