<?php

namespace App\Services;

use App\Models\Batch;
use PHPUnit\Event\Telemetry\System;

class BatchService {

    public function getDuration($batch_id)
    {
        $batch = Batch::with('course')->find($batch_id);
        $size_type = $batch->size_type;
        if(in_array($batch->course->type, ['Tahsin Anak', 'Tahsin Balita'])) {
            if($size_type == 'kecil') {
                return 60;
            } elseif($size_type == 'sedang') {
                return 75;
            } else {
                return 90;
            }
        } elseif($batch->course->type == 'Tahsin Dewasa') {
            if($size_type == 'kecil') {
                return 60;
            } elseif($size_type == 'sedang') {
                return 90;
            } elseif($size_type == 'besar') {
                return 120;
            } else {
                return 150;
            }
        }
        return (new SettingService)->value('durasi_'.str($batch->course->type)->snake(), 0);
    }

    public function getSizeType($type, $count)
    {
        $tipe = '';
        if(in_array($type, ['Tahsin Anak', 'Tahsin Balita'])) {
            if($count < 11)
                $tipe = 'kecil';
            elseif($count < 14)
                $tipe = 'sedang';
            elseif($count < 17)
                $tipe = 'besar';
            else
                $tipe = 'super_besar';
        } elseif($type == 'Tahsin Dewasa') {
            if($count < 10)
                $tipe = 'kecil';
            elseif($count < 13)
                $tipe = 'sedang';
            elseif($count < 16)
                $tipe = 'besar';
            else
                $tipe = 'super_besar';
        }
        return $tipe;
    }
}