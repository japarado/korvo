<?php

namespace App\Jobs;

use App\Mail\StudentEventReportMail;
use App\OSA;
use App\Student;
use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEventEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($student, $osa, string $recipient)
    {
        $this->student = $student;
        $this->osa = $osa;
        $this->recipient = $recipient;
    }

    public $student;
    public $osa;
    public $recipient;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $context = [
            'student' => $this->student,
            'osa' => $this->osa,
        ];
        $pdf = PDF::loadView('student.student-event-report', $context);
        Mail::to($this->recipient)->send(new StudentEventReportMail($this->student, $pdf, $this->osa));
    }
}
