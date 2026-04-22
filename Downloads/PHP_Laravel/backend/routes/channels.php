<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Private user channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private admin channel
Broadcast::channel('admin', function ($user) {
    return $user->hasRole('super_admin') || $user->hasRole('admin');
});

// Private server channel
Broadcast::channel('server.{serverId}', function ($user, $serverId) {
    return $user->servers()->where('server_id', $serverId)->exists();
});

// Private file channel
Broadcast::channel('file.{fileId}', function ($user, $fileId) {
    $file = \App\Models\AssessmentFile::find($fileId);
    if (!$file) return false;
    return $file->created_by === $user->id || $file->isSharedWith($user->id);
});

// Presence channel for online users
Broadcast::channel('online', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

// Presence channel for assessment monitoring
Broadcast::channel('assessment.{assessmentId}', function ($user, $assessmentId) {
    $assessment = \App\Models\AssessmentReport::find($assessmentId);
    if (!$assessment) return false;
    return $user->hasPermission('view_assessments') || $assessment->created_by === $user->id;
});