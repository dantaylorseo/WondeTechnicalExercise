<?php

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
// Uses the given trait in the current file
uses(RefreshDatabase::class);

test('an employee can be created', function () {
    $employee = Employee::factory()->create();
    
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
    ]);
});