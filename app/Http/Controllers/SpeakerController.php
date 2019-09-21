<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpeakerRequest;
use App\Speaker;
use App\Event;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $speakers = Speaker::all();
        return response()->json([
            'speakers' => $speakers
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
    public function store(SpeakerRequest $request)
    {
        $speaker = Speaker::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'description' => $request->input('description'),
        ]);
        return response()->json([
            'speaker' => $speaker
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
        $speaker = Speaker::find($id);
        return response()->json([
            'speaker' => $speaker
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
    public function update(SpeakerRequest $request, $id)
    {
        $speaker = Speaker::find($id);
        if($speaker)
        {
            $speaker->first_name = $request->input('first_name');
            $speaker->last_name = $request->input('last_name');
            $speaker->description = $request->input('description');
            $speaker->save();

            return response()->json([
                'speaker' => $speaker
            ]);
        }
        else 
        {
            return response()->json([
                'error' => "Speaker with an ID of $id not found"
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
        $speaker = Speaker::find($id);
        if($speaker)
        {
            $speaker->destroy();
            return response()->json([
                'speaker' => $speaker
            ]);
        }
        else 
        {
            return response()->json([
                'error' => "Speaker with an ID of $id not found"
            ]);
        }
    }

    public function assignToEvent($speaker_id, $event_id)
    {
        $speaker = Speaker::find($speaker_id);
        $event = Event::find($event_id);

        if($speaker && $event)
        {
            if($speaker->events()->where('event.id', $event_id)->get()->count())
            {
                return response()->json([
                    'error' => "Speaker with ID of $speaker_id has already been registered to event with ID of $event_id"
                ]);
            }
            else 
            {
                $speaker->events()->attach($event_id);
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

    public function removeFromEvent($speaker_id, $event_id)
    {
        $speaker = Speaker::find($speaker_id);
        $event = Event::find($event_id);

        if($speaker && $event)
        {
            if(!$speaker->events()->where('event.id', $event_id)->get()->count())
            {
                return response()->json([
                    'error' => "Speaker with an ID of $speaker_id is currently not associated with the event with an ID of $event_id"
                ]);
            }
            else 
            {
                $speaker->events()->detach($event_id);
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
