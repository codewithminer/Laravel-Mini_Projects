<?php

namespace App\Http\Controllers\Api;

//In Laravel 11, controllers should extend Illuminate\Routing\Controller 
//(not App\Http\Controllers\Controller
use Illuminate\Routing\Controller;

use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttendeeController extends Controller
{
    use CanLoadRelationships;
    use AuthorizesRequests;
    private array $relations = ['user'];

    public function __construct(){
     $this->middleware('auth:sanctum')->except(['index', 'show', 'update']);
     // 60 request in 1 minute
     $this->middleware('throttle:60,1')->only(['store','destroy']);
     $this->authorizeResource(Attendee::class, 'attendee');
    }

    public function index(Event $event)
    {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );
      return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        $attendee = $this->loadRelationships(
            $event->attendees()->create([
                'user_id' => $request->user()->id
            ])
        );

        return new AttendeeResource($attendee);
    }

    // the order of the parameters is important beacuse in the route we have {event}/{attendee}
    public function show(Event $event, Attendee $attendee)
    {
        return new AttendeeResource($this->loadRelationships($attendee));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    // we should get 204 status code if the attendee is deleted successfully
    public function destroy(Event $event, Attendee $attendee)
    {
        // if(Gate::denies('delete-attendee', [$event, $attendee])){
        //     abort(403,'You are not authorized to delete this attendee.');
        // }
        // $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response(status:204);
    }
}
