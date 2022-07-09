<?php

namespace App\Interfaces;

interface PaymentRepositoryInterface
{
    public function all();

    public function count();

    public function getByPeriod($period_id);

    public function countByPeriod($period_id);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function check($payment, $batch_id, $member_id);

    public function createDetail(array $data);
}
