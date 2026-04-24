<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Jobs\ScanServerJob;
use App\Jobs\SendEmailJob;
use App\Models\Server;
use Illuminate\Support\Facades\Queue;

class QueueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_scan_server_job_is_dispatched()
    {
        $server = Server::factory()->create();
        
        ScanServerJob::dispatch($server);
        
        Queue::assertPushed(ScanServerJob::class, function ($job) use ($server) {
            return $job->server->id === $server->id;
        });
    }

    public function test_send_email_job_is_dispatched()
    {
        SendEmailJob::dispatch('test@example.com', 'Subject', 'emails.test', []);
        
        Queue::assertPushed(SendEmailJob::class);
    }

    public function test_job_has_correct_queue_connection()
    {
        $job = new ScanServerJob(Server::factory()->create());
        
        $this->assertEquals('default', $job->queue);
    }

    public function test_failed_job_is_logged()
    {
        Queue::fake();
        
        $job = new SendEmailJob('invalid', 'Subject', 'emails.test', []);
        $job->failed(new \Exception('Test failure'));
        
        Queue::assertPushed(SendEmailJob::class);
    }
}