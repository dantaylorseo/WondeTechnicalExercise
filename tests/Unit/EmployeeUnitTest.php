<?php

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(RefreshDatabase::class);

test('an employee can be created', function () {
    $employee = Employee::factory()->create();
    
    $this->assertDatabaseHas('employees', [
        'id' => $employee->id,
    ]);
});