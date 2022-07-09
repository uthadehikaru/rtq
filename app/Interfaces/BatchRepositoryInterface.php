<?php

namespace App\Interfaces;

interface BatchRepositoryInterface
{
    public function all();

    public function count();

    public function getByCourse($course_id);

    public function countByCourse($course_id);

    public function getAvailableMembers($batch_id);

    public function getBatchMembers($keyword);

    public function members($batch_id);

    public function removeMember($batch_id, $member_id);

    public function getLatest($limit = 10);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function list();
}
