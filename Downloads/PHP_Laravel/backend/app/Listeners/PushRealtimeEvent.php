<?php

namespace App\Listeners;

use App\Events\AssessmentCompleted;
use App\Events\FileShared;
use App\Events\VulnerabilityDetected;
use App\Services\RealtimeService;

class PushRealtimeEvent extends BaseListener
{
    protected $realtime;

    public function __construct(RealtimeService $realtime)
    {
        $this->realtime = $realtime;
    }

    public function handleAssessmentCompleted(AssessmentCompleted $event)
    {
        $this->realtime->broadcastAssessmentComplete($event->assessmentId, $event->result);
    }

    public function handleVulnerabilityDetected(VulnerabilityDetected $event)
    {
        $this->realtime->broadcastNewVulnerability($event->vulnerability);
    }

    public function handleFileShared(FileShared $event)
    {
        $share = $event->share;
        $this->realtime->broadcastFileShared($share->file, $share->shared_with);
    }

    public function subscribe($events)
    {
        $events->listen(
            AssessmentCompleted::class,
            [PushRealtimeEvent::class, 'handleAssessmentCompleted']
        );
        $events->listen(
            VulnerabilityDetected::class,
            [PushRealtimeEvent::class, 'handleVulnerabilityDetected']
        );
        $events->listen(
            FileShared::class,
            [PushRealtimeEvent::class, 'handleFileShared']
        );
    }
}