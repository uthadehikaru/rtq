<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{

    public function getBalance()
    {
        $data = Transaction::selectRaw('sum(debit)-sum(credit) as balance')->first();
        return $data->balance;
    }
}