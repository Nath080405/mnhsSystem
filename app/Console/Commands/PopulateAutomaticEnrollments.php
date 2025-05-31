<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Services\AutomaticEnrollmentService;

class PopulateAutomaticEnrollments extends Command
{
    protected $signature = 'enrollments:populate';
    protected $description = 'Populate automatic enrollments for existing students';

    protected $enrollmentService;

    public function __construct(AutomaticEnrollmentService $enrollmentService)
    {
        parent::__construct();
        $this->enrollmentService = $enrollmentService;
    }

    public function handle()
    {
        $this->info('Starting to populate automatic enrollments...');

        $students = Student::all();
        $bar = $this->output->createProgressBar(count($students));

        foreach ($students as $student) {
            $this->enrollmentService->enrollStudent($student);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Automatic enrollments populated successfully!');
    }
} 