<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutomaticEnrollmentService;

class SyncGradeLevelEnrollments extends Command
{
    protected $signature = 'enrollments:sync-all';
    protected $description = 'Sync enrollments for all students in all grade levels';

    protected $enrollmentService;

    public function __construct(AutomaticEnrollmentService $enrollmentService)
    {
        parent::__construct();
        $this->enrollmentService = $enrollmentService;
    }

    public function handle()
    {
        $gradeLevels = [
            'Grade 7', 'Grade 8', 'Grade 9', 'Grade 10',
            'Grade 11', 'Grade 12'
        ];
        
        $this->info("Starting to sync enrollments for all grade levels...");
        
        $bar = $this->output->createProgressBar(count($gradeLevels));
        $bar->start();

        foreach ($gradeLevels as $gradeLevel) {
            try {
                $this->enrollmentService->syncGradeLevelEnrollments($gradeLevel);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nFailed to sync enrollments for {$gradeLevel}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info("Completed syncing enrollments for all grade levels!");
    }
} 