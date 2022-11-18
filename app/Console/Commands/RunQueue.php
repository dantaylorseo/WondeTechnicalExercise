<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Student;
use App\Models\Employee;
use App\Models\WondeClass;
use Illuminate\Console\Command;
use Wonde\Client as WondeClient;
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

        if ($employee->contact_details && $employee->contact_details->data && $employee->contact_details->data->emails && $employee->contact_details->data->emails->email) {
            User::updateOrCreate(
                [
                    'email' => $employee->contact_details->data->emails->email,
                ],
                [
                    'name' => (!empty( $employee->forename ) ? $employee->forename . ' ' : '') . $employee->surname,
                    'email' => $employee->contact_details->data->emails->email,
                    'password' => bcrypt('Password123'),
                    'employee_id' => $employee->id,
                ]
            );
        }

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
            
            $employee->classes()->updateOrCreate(
                [
                    'id' => $class->id,
                ],
                $validator->validated()
            );
            $this->_saveLessonsForClass( $class->id);
        });

        $this->info('Classes saved');
    }

    private function _saveStudentsForClass( $students, $class_id ) {
        collect($students)->each(function ($student) use ($class_id) {
            $validator = Validator::make(
                [
                    'id' => $student->id,
                    'upi' => $student->upi,
                    'mis_id' => $student->mis_id,
                    'initials' => $student->initials,
                    'surname' => $student->surname,
                    'forename' => $student->forename,
                    'middle_names' => $student->middle_names,
                    'legal_surname' => $student->legal_surname,
                    'legal_forename' => $student->legal_forename,
                ],
                [
                    'id' => 'required|string',
                    'upi' => 'required|string',
                    'mis_id' => 'required|string',
                    'initials' => 'nullable|string',
                    'surname' => 'required|string',
                    'forename' => 'nullable|string',
                    'middle_names' => 'nullable|string',
                    'legal_surname' => 'nullable|string',
                    'legal_forename' => 'nullable|string',
                ]
            );

            if ($validator->fails()) {
                $this->error('Validation failed');
                $this->error($validator->errors());
                return;
            }

            $createdStudent = Student::updateOrCreate(
                [
                    'id' => $student->id,
                ],
                $validator->validated()
            );
            $createdStudent->classes()->attach($class_id);
        });
        $this->info('Students saved');
    }

    private function _saveLessonsForClass($class_id) {
        $class = $this->school->classes->get($class_id, [
            'lessons,students'
        ]);

        $this->_saveStudentsForClass( $class->students->data ?? [], $class->id );

        collect($class->lessons->data ?? [])->each(function ($lesson) use ($class, $class_id) {
            $validator = Validator::make(
                [
                    'id' => $lesson->id,
                    'room' => $lesson->room,
                    'period' => $lesson->period,
                    'period_instance_id' => $lesson->period_instance_id,
                    'employee_id' => $lesson->employee,
                    'alternative' => $lesson->alternative,
                    'start_at' => $lesson->start_at->date,
                    'end_at' => $lesson->end_at->date,
                ],
                [
                    'id' => 'required|string',
                    'room' => 'nullable|string',
                    'period' => 'nullable|string',
                    'period_instance_id' => 'nullable|integer',
                    'employee_id' => 'nullable|string',
                    'alternative' => 'nullable|boolean',
                    'start_at' => 'required|date',
                    'end_at' => 'required|date',
                ]
            );
            
            if ($validator->fails()) {
                $this->error('Validation failed');
                $this->error($validator->errors());
                return;
            }
            
            $dbClass = WondeClass::find($class_id);
            $dbClass->lessons()->updateOrCreate(
                [
                    'id' => $lesson->id,
                ],
                $validator->validated()
            );
        });
    }
}
