<?php

namespace App\Http\Controllers;

use App\Mail\StudentEventReportMail;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($student_id)
    {
        $student = Student::where('id', $student_id)->with(['events', 'events.organization'])->first();
        $context = [
            'student' => $student
        ];
        if($student)
        {
            $pdf = PDF::loadView('student.student-event-report', $context);
            /* return $pdf->download('sample_pdf_laravel.pdf'); */
            Mail::to('fishbonesandzap@gmail.com')->send(new StudentEventReportMail($student, $pdf));
            return view('student.student-event-report')->with($context);
        }
        else 
        {
            return response()->json([
                'error' => "Student with an ID of $student_id not found"
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
