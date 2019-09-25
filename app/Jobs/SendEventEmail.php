<?php

namespace App\Jobs;

use App\OSA;
use App\Student;
use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEventEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Student $student, OSA $osa)
    {
        $this->student = $student;
        $this->osa = $osa;
    }

    public $student;
    public $osa;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $context = [
            'student' => Student::with(['events' => function($query) {
                $query->where('status', Config::get('constants.event_status.cleared'));
            }, 
            'events.organization',
            ])
                ->where('student.id', $id)
                ->first(),
            'osa' => $osa,
        ];
        $pdf = PDF::loadView('student.student-event-report', $context);
    }
}
