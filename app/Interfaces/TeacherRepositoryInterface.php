<?php

namespace App\Interfaces;

interface TeacherRepositoryInterface
{
    public function all();

    public function count();

    public function getLatest($limit = 10);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function list();
}
