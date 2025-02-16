<?php

use App\Models\Attendee;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider{
    
    protected $policies = [];

    public function boot():void{
        // Gate::define('update-event', function($user, Event $event){
        //     return $user->id === $event->user_id;
        // });

        // Gate::define('delete-attendee', function($user, Event $event, Attendee $attendee){
        //     Log::info('This is an info message');
        //     return $user->id === $event->user_id ||
        //         $user->id === $attendee->user_id;
        // });
    }
}