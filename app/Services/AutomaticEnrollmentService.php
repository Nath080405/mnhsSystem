<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Section;
use App\Models\AutomaticEnrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AutomaticEnrollmentService
{
    /**
     * Enroll a student in all subjects matching their grade level
     */
    public function enrollStudent(Student $student)
    {
        if (!$student->grade_level) {
            return;
        }

        // Get all subjects for the student's grade level
        $subjects = Subject::where('grade_level', $student->grade_level)
            ->whereNull('parent_id')  // Only get parent subjects (subject labels)
            ->get();

        foreach ($subjects as $subject) {
            // Create automatic enrollment record
            AutomaticEnrollment::updateOrCreate(
                [
                    'student_id' => $student->user_id,
                    'subject_id' => $subject->id,
                ],
                [
                    'grade_level' => $student->grade_level
                ]
            );

            // Create grade records for all grading periods if they don't exist
            $gradingPeriods = ['1st', '2nd', '3rd', '4th'];
            foreach ($gradingPeriods as $period) {
                DB::table('grades')->updateOrInsert(
                    [
                        'student_id' => $student->user_id,
                        'subject_id' => $subject->id,
                        'grading_period' => $period
                    ],
                    [
                        'grade' => null,
                        'remarks' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
            }
        }
    }

    /**
     * Update enrollments for all students in a section
     */
    public function updateSectionEnrollments(Section $section)
    {
        DB::beginTransaction();
        try {
            $students = Student::where('section', $section->name)->get();
            
            foreach ($students as $student) {
                $this->enrollStudent($student);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update enrollments when a student's section or grade level changes
     */
    public function updateStudentEnrollments(Student $student, string $oldGradeLevel, string $oldSection)
    {
        DB::beginTransaction();
        try {
            // Remove old enrollments
            $this->removeStudentEnrollments($student);
            
            // Create new enrollments
            $this->enrollStudent($student);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function removeStudentEnrollments(Student $student)
    {
        // Remove automatic enrollments
        $student->automaticEnrollments()->delete();
        
        // Remove grade records
        $student->grades()->delete();
    }

    /**
     * Sync all enrollments for a specific grade level and section
     */
    public function syncGradeLevelAndSectionEnrollments(string $gradeLevel, string $section)
    {
        $students = Student::where('grade_level', $gradeLevel)
            ->where('section', $section)
            ->get();

        $subjects = Subject::where('grade_level', $gradeLevel)
            ->whereNull('parent_id')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    // Create or update enrollment
                    AutomaticEnrollment::updateOrCreate(
                        [
                            'student_id' => $student->user_id,
                            'subject_id' => $subject->id,
                        ],
                        [
                            'grade_level' => $gradeLevel
                        ]
                    );

                    // Create grade records for all grading periods
                    $gradingPeriods = ['1st', '2nd', '3rd', '4th'];
                    foreach ($gradingPeriods as $period) {
                        DB::table('grades')->updateOrInsert(
                            [
                                'student_id' => $student->user_id,
                                'subject_id' => $subject->id,
                                'grading_period' => $period
                            ],
                            [
                                'grade' => null,
                                'remarks' => null,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get all students enrolled in a subject based on grade level and section
     */
    public function getEnrolledStudents(Subject $subject, ?string $section = null)
    {
        $query = Student::where('grade_level', $subject->grade_level)
            ->whereHas('automaticEnrollments', function ($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            });

        if ($section) {
            $query->where('section', $section);
        }

        return $query->get();
    }

    public function syncGradeLevelEnrollments(string $gradeLevel)
    {
        DB::beginTransaction();
        try {
            $students = Student::where('grade_level', $gradeLevel)->get();
            
            foreach ($students as $student) {
                $this->enrollStudent($student);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
} 