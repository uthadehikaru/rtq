<?php

namespace App\Services;

use App\Models\Batch;
use PHPUnit\Event\Telemetry\System;

class BatchService {

    public function getDuration($batch_id)
    {
        $batch = Batch::with('course')->find($batch_id);
        $size_type = $batch->size_type;
        if($batch->course->type == 'Tahsin Anak') {
            if($size_type == 'besar') {
                return 90;
            } elseif($size_type == 'sedang') {
                return 75;
            } else {
                return 60;
            }
        }elseif($batch->course->type == 'Tahsin Dewasa') {
            if($size_type == 'besar') {
                return 120;
            } elseif($size_type == 'sedang') {
                return 90;
            } else {
                return 60;
            }
        }
        return (new SettingService)->value('durasi_'.str($batch->course->type)->snake(), 0);
    }

    public function getSizeType($type, $count)
    {
        $tipe = '';
        if($type == 'Tahsin Anak') {
            if($count >= 14)
                $tipe = 'besar';
            elseif($count >= 11)
                $tipe = 'sedang';
            else
                $tipe = 'kecil';
        }elseif($type == 'Tahsin Dewasa') {
            if($count >= 13)
                $tipe = 'besar';
            elseif($count >= 10)
                $tipe = 'sedang';
            else
                $tipe = 'kecil';
        }
        return $tipe;
    }
}