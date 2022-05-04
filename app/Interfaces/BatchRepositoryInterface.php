<?php

namespace App\Interfaces;

interface BatchRepositoryInterface 
{
    public function getByCourse($course_id);
    public function countByCourse($course_id);
    public function getLatest($limit=10);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}