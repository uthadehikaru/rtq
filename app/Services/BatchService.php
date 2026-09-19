<?php

namespace App\Services;

use App\Models\Batch;

class BatchService
{
    public const DEWASA_EXTRA_MEMBER_THRESHOLD = 15;

    public const DEWASA_EXTRA_MEMBER_STEP = 3;

    public const DEWASA_EXTRA_FEE = 25000;

    public const DEWASA_EXTRA_DURATION_MINUTES = 15;

    public function getDuration($batch_id, $count = null)
    {
        $batch = Batch::with(['course', 'members'])->find($batch_id);

        return $this->calculateDuration($batch->course->type, $count ?? $batch->members->count());
    }

    public function calculateDuration($type, $count)
    {
        $sizeType = $this->getSizeType($type, $count);

        if (in_array($type, ['Tahsin Anak', 'Tahsin Balita'])) {
            if ($sizeType == 'kecil') {
                return 60;
            } elseif ($sizeType == 'sedang') {
                return 75;
            }

            return 90;
        } elseif ($type == 'Tahsin Dewasa') {
            if ($sizeType == 'kecil') {
                return 60;
            } elseif ($sizeType == 'sedang') {
                return 90;
            } elseif ($sizeType == 'besar') {
                return 120;
            }

            return 120 + $this->getDewasaExtraMultiplier($count) * self::DEWASA_EXTRA_DURATION_MINUTES;
        }

        return (new SettingService)->value('durasi_'.str($type)->snake(), 0);
    }

    public function getSizeType($type, $count)
    {
        $tipe = '';
        if (in_array($type, ['Tahsin Anak', 'Tahsin Balita'])) {
            if ($count < 11) {
                $tipe = 'kecil';
            } elseif ($count < 14) {
                $tipe = 'sedang';
            } elseif ($count < 17) {
                $tipe = 'besar';
            } else {
                $tipe = 'super_besar';
            }
        } elseif ($type == 'Tahsin Dewasa') {
            if ($count < 10) {
                $tipe = 'kecil';
            } elseif ($count < 13) {
                $tipe = 'sedang';
            } elseif ($count < 16) {
                $tipe = 'besar';
            } else {
                $tipe = 'super_besar';
            }
        }

        return $tipe;
    }

    public function getDewasaExtraMultiplier($count)
    {
        if ($count <= self::DEWASA_EXTRA_MEMBER_THRESHOLD) {
            return 0;
        }

        return (int) ceil(($count - self::DEWASA_EXTRA_MEMBER_THRESHOLD) / self::DEWASA_EXTRA_MEMBER_STEP);
    }
}
