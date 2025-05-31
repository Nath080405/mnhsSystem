<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AutomaticEnrollmentService;
use App\Models\Section;

class SyncAllEnrollments extends Command
{
    protected $signature = 'enrollments:sync-all';
    protected $description = 'Sync enrollments for all students in all sections and grade levels';

    protected $enrollmentService;

    public function __construct(AutomaticEnrollmentService $enrollmentService)
    {
        parent::__construct();
        $this->enrollmentService = $enrollmentService;
    }

    public function handle()
    {
        $this->info('Starting enrollment sync for all sections...');

        $sections = Section::all();
        $bar = $this->output->createProgressBar(count($sections));
        $bar->start();

        foreach ($sections as $section) {
            try {
                $this->enrollmentService->updateSectionEnrollments($section);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nError syncing section {$section->name}: " . $e->getMessage());
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('Enrollment sync completed successfully!');
    }
} 