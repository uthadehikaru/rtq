<?php

use App\Services\BatchService;
use App\Services\SalaryService;

test('tahsin dewasa extra multiplier uses groups of 3 members above 15', function () {
    $service = new BatchService();

    expect($service->getDewasaExtraMultiplier(15))->toBe(0);
    expect($service->getDewasaExtraMultiplier(16))->toBe(1);
    expect($service->getDewasaExtraMultiplier(18))->toBe(1);
    expect($service->getDewasaExtraMultiplier(19))->toBe(2);
    expect($service->getDewasaExtraMultiplier(21))->toBe(2);
    expect($service->getDewasaExtraMultiplier(22))->toBe(3);
});

test('tahsin dewasa duration adds 15 minutes for each 3 members above 15', function () {
    $service = new BatchService();

    expect($service->calculateDuration('Tahsin Dewasa', 9))->toBe(60);
    expect($service->calculateDuration('Tahsin Dewasa', 12))->toBe(90);
    expect($service->calculateDuration('Tahsin Dewasa', 15))->toBe(120);
    expect($service->calculateDuration('Tahsin Dewasa', 16))->toBe(135);
    expect($service->calculateDuration('Tahsin Dewasa', 18))->toBe(135);
    expect($service->calculateDuration('Tahsin Dewasa', 19))->toBe(150);
    expect($service->calculateDuration('Tahsin Dewasa', 21))->toBe(150);
    expect($service->calculateDuration('Tahsin Dewasa', 22))->toBe(165);
});

test('tahsin dewasa salary adds 25000 for each 3 members above 15', function () {
    $service = new SalaryService();

    expect($service->getRate('tahsin_dewasa', 'kecil', 0, 9))->toBe(50000);
    expect($service->getRate('tahsin_dewasa', 'sedang', 0, 12))->toBe(75000);
    expect($service->getRate('tahsin_dewasa', 'besar', 0, 15))->toBe(100000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 0, 16))->toBe(125000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 0, 18))->toBe(125000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 0, 19))->toBe(150000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 0, 21))->toBe(150000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 0, 22))->toBe(175000);
});

test('tahsin anak duration and salary are unchanged by dewasa extra rule', function () {
    $batchService = new BatchService();
    $salaryService = new SalaryService();

    expect($batchService->calculateDuration('Tahsin Anak', 18))->toBe(90);
    expect($salaryService->getRate('tahsin_anak', 'super_besar', 0, 18))->toBe(75000);
});
