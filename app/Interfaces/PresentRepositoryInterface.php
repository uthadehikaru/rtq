<?php

namespace App\Interfaces;

interface PresentRepositoryInterface
{
    public function getBySchedule($schedule_id);
    
    public function getByTeacher($teacher_id);

    public function count($schedule_id);

    public function getLatest($limit = 10);

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
}
