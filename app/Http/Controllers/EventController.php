<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Http\Requests\CreateEvent;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = static::getCurrentUser();
        $role_user = static::getUserRoleInstance();

        if($user->role_id == Config::get('constants.roles.organization'))
        {
            $events = $role_user->events;
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
            $events = Event::whereIn('status', [
                Config::get('constants.event_status.osa_approval'),
                Config::get('constants.event_status.archived'),
            ])->get();
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
        $user = static::getCurrentUser();
        $event = new Event();
        $event->name = $request->get('name');
        $event->academic_year = $request->get('academic_year');
        $event->date_start = $request->get('date_start');
        $event->status = $request->get('status');
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
            $event = $role_user->events()->find($id);
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
        //
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
        $event->name = $request->get('name');
        $event->academic_year = $request->get('academic_year');
        $event->date_start = $request->get('date_start');
        $event->status = $request->get('status');
        $event->organization_id = $user->id;
        $event->save();

        return response()->json([
            'event' => $event
        ]);
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
                'status' => 'Event not found'
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
                        'status' => 'Error. That event has already been approved or is not yet ready for approval'
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
                        'status' => 'Error. That event has already been approved or is not yet ready for approval'
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
                'status' => 'Error. Event not found'
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
                        'status' => 'Error. That event has already been rejected or is not yet ready for rejection'
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
                        'status' => 'Error. That event has already been rejected or is not yet ready for rejection'
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
                'status' => 'Error. Event not found'
            ]);
        }
    }

    public function archived()
    {
        $archived_events = Event::withTrashed()->get();
        return response()->json([
            'events' => $archived_events
        ]);
    }
}
