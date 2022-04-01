<?php

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('public', function () {
    return true;
});

Broadcast::channel('private.{id}', function ($user, $id) {

    return (int) $user->id === (int) $id;
});

Broadcast::channel('presence.{groupId}', function ($user,int $groupId) {
    
    if ($user->canJoinGroup($groupId)) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});