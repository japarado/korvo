<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentEventReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student, $pdf)
    {
        $this->student = $student;
        $this->pdf = $pdf;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('student.student-event-report')
                    ->with([
                        'student' => $this->student,
                    ])
                    ->attachData($this->pdf->output(), 'eventreport.pdf', [
                        'mime' => 'application/pdf'
                    ]);
    }
}
