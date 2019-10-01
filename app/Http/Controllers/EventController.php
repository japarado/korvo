<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Http\Requests\CreateEvent;
use App\Speaker;
use App\Student;
use Illuminate\Support\Facades\Config;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = static::getCurrentUser();
        $role_user = static::getUserRoleInstance();

        if($user->role_id == Config::get('constants.roles.organization'))
        {
            /* $events = $role_user->events()->with('speakers')->with('students')->get(); */
            $events = $role_user->events()->get();
        }
        elseif($user->role_id == Config::get('constants.roles.socc'))
        {
            $events = Event::whereIn('status', [
                Config::get('constants.event_status.socc_approval'),
                Config::get('constants.event_status.osa_rejection'),
            ])->get();
        }
        elseif($user->role_id == Config::get('constants.roles.osa'))
        {
            if($request->input('all') == true)
            {
                $events = Event::all();
            }
            else 
            {
                $events = Event::whereIn('status', [
                    Config::get('constants.event_status.osa_approval'),
                    Config::get('constants.event_status.archived'),
                ])->get();
            }
        }

        return response()->json([
            /* 'user' => $user, */
            /* 'role_user' => $role_user, */
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEvent $request)
    {
        /* if(Event::where('ereserve_id', $request->input('ereserve_id'))->count()) */
        /* { */
        /*     return response() */
        /* } */
        $user = static::getCurrentUser();
        $event = new Event();
        $event->name = $request->input('name');
        $event->academic_year = $request->input('academic_year');
        $event->date_start = $request->input('date_start');
        $event->status = $request->input('status'); 
        $event->ereserve_id = $request->input('ereserve_id');
        $event->read_status = $request->input('read_status');
        $event->classification = $request->input('classification');
        $event->organization_id = $user->id;
        $event->save();

        return response()->json([
            'event' => $event
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = static::getCurrentUser();
        $role_user = static::getUserRoleInstance();
        if($user->role_id == Config::get('constants.roles.organization'))
        {
            $event = $role_user->events()->where('event.id', $id)->with('speakers')->with('students')->get();
            /* $event = $role_user->events()->find($id); */
        }
        elseif($user->role_id == Config::get('constants.roles.socc'))
        {
            $event = Event::whereIn('status',[
                Config::get('constants.event_status.socc_approval'),
                Config::get('constants.event_status.osa_rejection'),
            ])
                ->where('id', $id)
                ->get();
        }
        elseif($user->role_id == Config::get('constants.roles.osa'))
        {
            $event = Event::whereIn('status',[
                Config::get('constants.event_status.osa_approval'),
                Config::get('constants.event_status.archived'),
            ])
                ->where('id', $id)
                ->get();
        }
        return response()->json([
            'event' => $event
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
    public function update(CreateEvent $request, $id)
    {
        $user = static::getCurrentUser();
        $event = Event::find($id);

        if(!in_array($event->status, [Config::get('constants.event_status.draft'), Config::get('constants.event_status.socc_rejection')]))
        {
            return response()->json([
                'error' => 'The status of the event no longer allows updates'
            ]);
        }
        elseif(!in_array($request->input('status'), [
            Config::get('constants.event_status.draft'),
            Config::get('constants.event_status.socc_approval'),
            Config::get('constants.event_status.socc_rejection')
        ]))
        {
            // Prevent them from updating the status to an unallowed status code (e.g. setting it to cleared)
            return response()->json([
                'error' => 'Invalid status code'
            ]);
        }
        else 
        {
            $event->name = $request->input('name');
            $event->academic_year = $request->input('academic_year');
            $event->date_start = $request->input('date_start');
            $event->status = $request->input('status');
            $event->classification = $request->input('classification');
            $event->ereserve_id = $request->input('ereserve_id');
            $event->read_status = $request->input('read_status');
            $event->organization_id = $user->id;
            $event->save();

            return response()->json([
                'event' => $event
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
        $event = Event::find($id);
        if($event)
        {
            $event->status = Config::get('constants.event_status.archived');
            $event->save();
            $event->delete();
            return response()->json([
                'event' => $event
            ]);
        }
        else 
        {
            return response()->json([
                'error' => 'Event not found'
            ]);
        }
    }

    public function approve(Request $request, $id)
    {
        $user = static::getCurrentUser();
        $event = Event::find($id);

        if($event)
        {
            if($user->role_id == Config::get('constants.roles.socc'))
            {
                if(!in_array($event->status, [Config::get('constants.event_status.socc_approval'), Config::get('constants.event_status.osa_rejection')]))
                {
                    return response()->json([
                        'error' => 'Error. That event has already been approved or is not yet ready for approval'
                    ]);
                }
                else 
                {
                    $event->socc_id = $user->id;
                    $event->status = Config::get('constants.event_status.osa_approval');
                    $event->save();
                    return response()->json([
                        'event' => $event
                    ]);
                }
            }
            elseif($user->role_id == Config::get('constants.roles.osa'))
            {
                if($event->status != Config::get('constants.event_status.osa_approval'))
                {
                    return response()->json([
                        'error' => 'Error. That event has already been approved or is not yet ready for approval'
                    ]);
                }
                else 
                {
                    $event->osa_id = $user->id;
                    $event->status = Config::get('constants.event_status.cleared');
                    $event->save();
                    return response()->json([
                        'event' => $event
                    ]);
                }
            }

        }
        else 
        {
            return response()->json([
                'error' => 'Event not found'
            ]);
        }
    }

    public function reject(Request $request, $id)
    {
        $user = static::getCurrentUser();
        $event = Event::find($id);

        if($event)
        {
            if($user->role_id == Config::get('constants.roles.socc'))
            {
                if(!in_array($event->status, [Config::get('constants.event_status.socc_approval'), Config::get('constants.event_status.osa_rejection')]))
                {
                    return response()->json([
                        'error' => 'That event has already been rejected or is not yet ready for rejection'
                    ]);
                }
                else 
                {
                    $event->socc_id = $user->id;
                    $event->status = Config::get('constants.event_status.socc_rejection');
                    $event->notes = $request->input('notes');
                    $event->save();
                    return response()->json([
                        'event' => $event
                    ]);
                }
            }
            elseif($user->role_id == Config::get('constants.roles.osa'))
            {
                if($event->status != Config::get('constants.event_status.osa_approval'))
                {
                    return response()->json([
                        'error' => 'That event has already been rejected or is not yet ready for rejection'
                    ]);
                }
                else 
                {
                    $event->osa_id = $user->id;
                    $event->status = Config::get('constants.event_status.osa_rejection');
                    $event->notes = $request->input('notes');
                    $event->save();
                    return response()->json([
                        'event' => $event
                    ]);
                }
            }

        }
        else 
        {
            return response()->json([
                'error' => 'Event not found'
            ]);
        }
    }

    public function archived()
    {
        $archived_events = Event::onlyTrashed()->get();
        return response()->json([
            'events' => $archived_events
        ]);
    }

    public function addStudent(Request $request, $event_id, $student_id)
    {
        $student = Student::find($student_id);
        $event = Event::find($event_id);

        if($event && $student)
        {
            if($event->students()->where('student.id', $student_id)->get()->count())
            {
                return response()->json([
                    'error' => "Event with ID of $event_id already has a student with and ID of $student_id"
                ]);
            }
            else 
            {
                $event->students()->attach($student_id, ['involvement' => $request->input('involvement')]);
                return response()->json([
                    'student' => Student::find($student_id),
                    'event' => Event::find($event_id),
                    'involvement' => $request->input('involvement'),
                ]);
            }
        }
        else 
        {
            $errors = [];
            if(!$student)
            {
                array_push($errors, "Student with ID of $student_id not found");
            }
            if(!$event)
            {
                array_push($errors, "Event with ID of $event_id not found");
            }
            if($errors)
            {
                return response()->json([
                    'errors' => $errors
                ]);
            }
        }
    }

    public function removeStudent($event_id, $student_id)
    {
        $student = Student::find($student_id);
        $event = Event::find($event_id);

        if($event && $student)
        {
            if(!$event->students()->where('student.id', $student_id)->get()->count())
            {
                return response()->json([
                    'error' => "Student with an ID of $student_id is currently not associated with the event with an ID of $event_id"
                ]);
            }
            else 
            {
                $event->students()->detach($student_id);
                return response()->json([
                    'student' => Student::find($student_id),
                    'event' => Event::find($event_id),
                ]);
            }
        }
        else 
        {
            $errors = [];
            if(!$student)
            {
                array_push($errors, "Student with ID of $student_id not found");
            }
            if(!$event)
            {
                array_push($errors, "Event with ID of $event_id not found");
            }
            if($errors)
            {
                return response()->json([
                    'errors' => $errors
                ]);
            }
        }
    }

    public function addSpeaker($event_id, $speaker_id)
    {
        $speaker = Speaker::find($speaker_id);
        $event = Event::find($event_id);

        if($event && $speaker)
        {
            if($event->speakers()->where('speaker.id', $speaker_id)->get()->count())
            {
                return response()->json([
                    'error' => "Event with ID of $event_id already has a speaker with an ID of $speaker_id"
                ]);
            }
            else 
            {
                $event->speakers()->attach($speaker_id);
                return response()->json([
                    'speaker' => Student::find($speaker_id),
                    'event' => Event::find($event_id),
                ]);
            }
        }
        else 
        {
            $errors = [];
            if(!$speaker)
            {
                array_push($errors, "Speaker with ID of $speaker_id not found");
            }
            if(!$event)
            {
                array_push($errors, "Event with ID of $event_id not found");
            }
            if($errors)
            {
                return response()->json([
                    'errors' => $errors
                ]);
            }
        }
    }

    public function removeSpeaker($event_id, $speaker_id)
    {
        $speaker = Speaker::find($speaker_id);
        $event = Event::find($event_id);

        if($event && $speaker)
        {
            if(!$event->speakers()->where('speaker.id', $speaker_id)->get()->count())
            {
                return response()->json([
                    'error' => "Speaker with an ID of $speaker_id is currently not associated with the event with an ID of $event_id"
                ]);
            }
            else 
            {
                $event->speakers()->detach($speaker_id);
                return response()->json([
                    'speaker' => Speaker::find($speaker_id),
                    'event' => Event::find($event_id),
                ]);
            }
        }
        else 
        {
            $errors = [];
            if(!$speaker)
            {
                array_push($errors, "Speaker with ID of $speaker_id not found");
            }
            if(!$event)
            {
                array_push($errors, "Event with ID of $event_id not found");
            }
            if($errors)
            {
                return response()->json([
                    'errors' => $errors
                ]);
            }
        }
    }
}
