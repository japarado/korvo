<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\StudentRequest;
use App\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'students' => $students
        ]);
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
    public function store(StudentRequest $request)
    {
        $student = Student::where('student_id', $request->student_id)->first();
        if($student)
        {
            return response()->json([
                'error' => "Student with the STUDENT_ID of {$request->input('student_id')} already exists",
            ]);
        }
        else 
        {
            $student = Student::create([
                'student_id' => $request->input('student_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'middle_initial' => $request->input('middle_initial')
            ]);
            return response()->json([
                'student' => $student
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id)->with('events')->get();
        return response()->json([
            'student' => $student
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request, $id)
    {
        $student = Student::find($id);
        if($student)
        {
            $student->last_name = $request->input('last_name');
            $student->first_name = $request->input('first_name');
            $student->middle_initial = $request->input('middle_initial');
            $student->save();

            return response()->json([
                'student' => $student
            ]);
        }
        else 
        {
            return response()->json([
                'error' => 'Student not found'
            ]);
        }
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

    public function assignToEvent(Request $request, $student_id, $event_id)
    {
        
        $student = Student::find($student_id);
        $event = Event::find($event_id);

        if($student && $event)
        {
            if($student->events()->where('event_id', $event_id)->where('student_id', $student_id)->get()->count())
            {
                return response()->json([
                    'error' => "Student with ID of $student_id has already been registered to event with ID of $event_id"
                ]);
            }
            else 
            {
                $student->events()->attach($event_id, ['involvement' => $request->input('involvement')]);
                return response()->json([
                    'student' => Student::find($student_id),
                    'event' => Event::find($event_id),
                    'involvement' => $request->input('involvement'),
                ]);
            }
        }
        else 
        {
        }
    }
}
