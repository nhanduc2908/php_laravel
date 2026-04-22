<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Events\AssessmentCompleted;
use App\Events\VulnerabilityDetected;
use App\Listeners\SendAlertOnVulnerability;
use App\Listeners\UpdateDashboardCache;
use Illuminate\Support\Facades\Event;

class EventTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_assessment_completed_event_is_dispatched()
    {
        $assessmentData = ['server_id' => 1, 'score' => 85.5];
        
        event(new AssessmentCompleted($assessmentData));
        
        Event::assertDispatched(AssessmentCompleted::class);
    }

    public function test_vulnerability_detected_event_has_listener()
    {
        Event::assertListening(
            VulnerabilityDetected::class,
            SendAlertOnVulnerability::class
        );
    }

    public function test_assessment_completed_triggers_cache_update()
    {
        Event::assertListening(
            AssessmentCompleted::class,
            UpdateDashboardCache::class
        );
    }

    public function test_event_broadcasting()
    {
        $event = new AssessmentCompleted(['server_id' => 1]);
        
        $this->assertArrayHasKey('server_id', $event->broadcastWith());
        $this->assertEquals('assessment.completed', $event->broadcastAs());
    }
}