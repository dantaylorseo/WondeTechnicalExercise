<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Wonde\Client as WondeClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class RunQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulates a queue runner to get data from Wonde';


    /**
     * Wonde client instance
     */
    protected WondeClient $client;

    /**
     * Wonde school instance
     */
    protected $school;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(WondeClient $client)
    {
        $this->client = $client;

        $this->info("Getting school from Wonde");
        $this->school = $this->client->school(config('wonde.school_id', null));

        $this->info('Getting employees from Wonde');
        $employees = $this->_getEmployee();

        return Command::SUCCESS;
    }

    private function _getEmployee()
    {
        $employee = $this->school->employees->get('A921160679', [
            'classes,contact_details'
        ]);
        
        $validator = Validator::make(
            [
                'id' => $employee->id,
                'upi' => $employee->upi,
                'mis_id' => $employee->mis_id,
                'title' => $employee->id,
                'initials' => $employee->initials,
                'surname' => $employee->surname,
                'forename' => $employee->forename,
                'middle_names' => $employee->middle_names,
                'legal_surname' => $employee->legal_surname,
                'legal_forename' => $employee->legal_forename,
                'gender' => $employee->gender,
                'date_of_birth' => $employee->date_of_birth,
            ],
            [
                'id' => 'required|string',
                'upi' => 'required|string',
                'mis_id' => 'required|string',
                'title' => 'nullable|string',
                'initials' => 'nullable|string',
                'surname' => 'required|string',
                'forename' => 'nullable|string',
                'middle_names' => 'nullable|string',
                'legal_surname' => 'nullable|string',
                'legal_forename' => 'nullable|string',
                'gender' => 'nullable|string',
                'date_of_birth' => 'nullable|date',
            ]
        );

        if ($validator->fails()) {
            $this->error('Validation failed');
            $this->error($validator->errors());
            return;
        }

        $createdEmployee = Employee::updateOrCreate(
            [
                'id' => $employee->id,
            ],
            $validator->validated()
        );


        $this->info('Employee saved');

        $this->_saveClassesToEmployee($employee->classes->data ?? [], $createdEmployee);

    }

    private function _saveClassesToEmployee(array $classes, Employee $employee)
    {
        collect($classes)->each(function ($class) use ($employee) {      
            $validator = Validator::make(
                [
                    'id' => $class->id,
                    'mis_id' => $class->mis_id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'description' => $class->description,
                    'subject' => $class->subject,
                    'alternative' => $class->alternative,
                ],
                [
                    'id' => 'required|string',
                    'mis_id' => 'required|string',
                    'name' => 'required|string',
                    'code' => 'nullable|string',
                    'description' => 'nullable|string',
                    'subject' => 'nullable|string',
                    'alternative' => 'nullable|string',
                ]
            );
            
            if ($validator->fails()) {
                $this->error('Validation failed');
                $this->error($validator->errors());
                return;
            }
            // dd($validator->validated());
            $employee->classes()->updateOrCreate(
                [
                    'id' => $class->id,
                ],
                $validator->validated()
            );
        });
    }
}
