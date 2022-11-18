<?php

use App\Models\WondeClass;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('RunQueue command adds required employee', function () {
    Artisan::call('run:queue');

    $this->assertDatabaseHas('employees', [
        'id' => 'A921160679',
    ]);
});

test('RunQueue command adds required classes', function () {
    Artisan::call('run:queue');

    $classes = WondeClass::all();

    $this->assertCount(7, $classes->toArray());
});