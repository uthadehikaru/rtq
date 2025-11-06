<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MemberRepository;
use App\Repositories\PresentRepository;
use App\Repositories\ScheduleRepository;
use Illuminate\Http\Request;

class MemberPresent extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, ScheduleRepository $scheduleRepository, PresentRepository $presentRepository, 
    MemberRepository $memberRepository, $schedule_id)
    {
        $schedule = $scheduleRepository->find($schedule_id);
        if (! $schedule) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }
        $qrcode = $request->get('qrcode');
        // Extract member_no from the end of the qrcode URL
        $member_no = null;
        if ($qrcode) {
            if (preg_match('/([A-Z]{3,}[0-9]+)/', $qrcode, $matches)) {
                $member_no = $matches[1];
            } else {
                // fallback, try to get after last slash
                $parts = explode('/', rtrim($qrcode, '/'));
                $member_no = end($parts);
            }
        }
        if (!$member_no) {
            return response()->json(['error' => 'Invalid QR code'], 400);
        }
        $member = $memberRepository->findByMemberNo($member_no);
        if (! $member) {
            return response()->json(['error' => 'Peserta Tidak ditemukan '.$member_no], 400);
        }
        $present = $presentRepository->getPresentByUser($schedule->id, $member->user_id);
        if (! $present) {
            $present = $presentRepository->create([
                'schedule_id' => $schedule->id,
                'user_id' => $member->user_id,
                'status' => 'present',
                'type' => 'member',
                'is_transfer' => true,
                'attended_at' => now(),
            ]);
        }else{
            $present->update([
                'status' => 'present',
                'attended_at' => now(),
            ]);
        }
        $present->load('user');
        return response()->json($present);
    }
}
