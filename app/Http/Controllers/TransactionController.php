<?php

namespace App\Http\Controllers;

use App\DataTables\TransactionsDataTable;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionsDataTable $datatable, TransactionRepository $transactionRepository)
    {
        $data['balance'] = $transactionRepository->getBalance();

        return $datatable->render('datatables.transaction', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['transaction'] = null;

        return view('forms.transaction', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required',
            'type' => 'required|in:debit,credit',
            'nominal' => 'required|numeric',
        ]);

        Transaction::create([
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'],
            'debit' => $data['type'] == 'debit' ? $data['nominal'] : 0,
            'credit' => $data['type'] == 'credit' ? $data['nominal'] : 0,
        ]);

        return to_route('transactions.index')->with('message', 'Berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['transaction'] = Transaction::find($id);

        return view('forms.transaction', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'transaction_date' => 'required|date',
            'description' => 'required',
            'type' => 'required|in:debit,credit',
            'nominal' => 'required|numeric',
        ]);

        Transaction::where('id', $id)->update([
            'transaction_date' => $data['transaction_date'],
            'description' => $data['description'],
            'debit' => $data['type'] == 'debit' ? $data['nominal'] : 0,
            'credit' => $data['type'] == 'credit' ? $data['nominal'] : 0,
        ]);

        return to_route('transactions.index')->with('message', 'Berhasil diperbaharui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::find($id)->delete();
        $data['statusCode'] = 200;

        return response()->json($data);
    }
}
