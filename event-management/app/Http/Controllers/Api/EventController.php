<?php

namespace App\Http\Controllers\Api;

//In Laravel 11, controllers should extend Illuminate\Routing\Controller 
//(not App\Http\Controllers\Controller
use Illuminate\Routing\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Http\Traits\CanLoadRelationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\RateLimiter;

class EventController extends Controller
{
    use CanLoadRelationships;
    use AuthorizesRequests; // we are using this trait to use `authorizeResource` method and $this->authorize() method
    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct(){
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        // 60 request in 1 minute
        $this->middleware('throttle:60,1')->only(['update','store','destroy']);
        $this->authorizeResource(Event::class, 'event');
    }

    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        // we loading events with `user` and 'attendees' relationships
        return EventResource::collection(
            $query->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);
        $event = Event::create([...$validate,'user_id'=>$request->user()->id]);
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // load `user` and 'attendees' relationship with event.
        $event->load('user','attendees');
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // if(Gate::denies('update-event', $event)){
        //     abort(403, 'You are not authorized to update this event.');
        // }

        $validate = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
        ]);
        $event->update($validate);
        // return new EventResource($event);
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
       $event->delete();
       return response()->json([
        'message' => 'Event Deleted Successfully.'
       ]);
    }
}
