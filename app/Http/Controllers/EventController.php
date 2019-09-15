<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'academic_year' => 'required',
            'status' => [
                'required',
                'numeric',
                Rule::in([
                    Config::get('constants.event_status.draft'),
                    Config::get('constants.event_status.socc_approval'),
                ]),
            ],
            'date_start' => 'required|date'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json($validator->errors());
        }
        else 
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
        $event = Event::find($id);
        $event->delete();
        return response()->json([
            'message' => 'Deleted',
            'event' => $event
        ]);
    }
}
