<?php

namespace Tests\Mock;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\ApiCallService;

class ExternalApiMockTest extends TestCase
{
    protected function tearDown(): void
    {
        Http::flushStoredCalls();
        parent::tearDown();
    }

    public function test_cve_api_mock_success()
    {
        Http::fake([
            'https://services.nvd.nist.gov/rest/json/cves/2.0*' => Http::response([
                'vulnerabilities' => [
                    [
                        'cve' => [
                            'id' => 'CVE-2024-12345',
                            'descriptions' => [['value' => 'Test vulnerability description']],
                            'metrics' => [
                                'cvssMetricV31' => [[
                                    'cvssData' => [
                                        'baseScore' => 7.5,
                                        'baseSeverity' => 'HIGH'
                                    ]
                                ]]
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $apiService = new ApiCallService();
        $result = $apiService->callCVEAPI('CVE-2024-12345');

        $this->assertNotNull($result);
        $this->assertEquals('CVE-2024-12345', $result['id']);
        $this->assertEquals(7.5, $result['cvss_score']);
    }

    public function test_cve_api_mock_not_found()
    {
        Http::fake([
            'https://services.nvd.nist.gov/rest/json/cves/2.0*' => Http::response([
                'vulnerabilities' => []
            ], 200)
        ]);

        $apiService = new ApiCallService();
        $result = $apiService->callCVEAPI('CVE-2024-99999');

        $this->assertNull($result);
    }

    public function test_cve_api_mock_timeout()
    {
        Http::fake([
            'https://services.nvd.nist.gov/rest/json/cves/2.0*' => Http::response(null, 500)
        ]);

        $apiService = new ApiCallService();
        $result = $apiService->callCVEAPI('CVE-2024-12345');

        $this->assertNull($result);
    }

    public function test_webhook_mock_success()
    {
        Http::fake([
            'https://webhook.site/test*' => Http::response(['status' => 'ok'], 200)
        ]);

        $apiService = new ApiCallService();
        $result = $apiService->sendWebhook('https://webhook.site/test', ['event' => 'test']);

        $this->assertNotNull($result);
        Http::assertSent(function ($request) {
            return $request->url() == 'https://webhook.site/test' &&
                   $request->method() == 'POST';
        });
    }

    public function test_external_api_retry_on_failure()
    {
        Http::fake([
            'https://api.example.com/data*' => Http::response(null, 503)
        ]);

        $apiService = new ApiCallService();
        $result = $apiService->makeRequest('GET', 'https://api.example.com/data');

        $this->assertNull($result);
        Http::assertSentCount(3); // Should retry 3 times
    }
}