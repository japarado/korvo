<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class StudentEventReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student, $pdf, $osa)
    {
        $this->student = $student;
        $this->pdf = $pdf;
        $this->osa = $osa;
    }

    public $student;
    public $pdf;
    public $osa;


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $filename = strtoupper($this->student->last_name)  . ", " . strtoupper($this->student->first_name) . " " . Carbon::now()->toDateString() . ".pdf";
        $student_name = strtoupper($this->student->last_name) . ", " . strtoupper($this->student->first_name);
        return $this
            ->view('student.report-email');
            /* ->text("Attached herewith is the report of student events for $student_name, Student number: {$this->student->student_number}"); */
        /* return $this */
        /*     ->view('student.student-event-report') */
        /*     ->attachData($this->pdf->output(), $filename, [ */
        /*         'mime' => 'application/pdf' */
        /*     ]); */
    }
}
