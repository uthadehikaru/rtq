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

test('tahsin fee is the hourly rate times class hours for its size', function () {
    $service = new SalaryService();

    // Tahsin Anak / Balita: kecil 1h, sedang 1.25h, besar & super besar 1.5h
    expect($service->getRate('tahsin_anak', 'kecil', 50000, 10))->toBe(50000);
    expect($service->getRate('tahsin_anak', 'sedang', 50000, 11))->toBe(62500);
    expect($service->getRate('tahsin_anak', 'besar', 50000, 16))->toBe(75000);
    expect($service->getRate('tahsin_anak', 'super_besar', 50000, 18))->toBe(75000);
    expect($service->getRate('tahsin_balita', 'sedang', 60000, 12))->toBe(75000);

    // Tahsin Dewasa: kecil 1h, sedang 1.5h, besar 2h, +0.25h per 3 members above 15
    expect($service->getRate('tahsin_dewasa', 'kecil', 50000, 9))->toBe(50000);
    expect($service->getRate('tahsin_dewasa', 'sedang', 50000, 12))->toBe(75000);
    expect($service->getRate('tahsin_dewasa', 'besar', 50000, 15))->toBe(100000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 50000, 16))->toBe(112500);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 50000, 19))->toBe(125000);
    expect($service->getRate('tahsin_dewasa', 'super_besar', 50000, 22))->toBe(137500);
});

test('non tahsin courses keep their flat configured fee', function () {
    $service = new SalaryService();

    expect($service->getRate('talaqqi_jamai', '', 40000, 30))->toBe(40000);
});
