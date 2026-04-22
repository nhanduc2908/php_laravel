<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiCallService
{
    protected $timeout = 30;
    protected $retries = 3;
    
    public function callCVEAPI($cveId)
    {
        $cacheKey = "cve:{$cveId}";
        
        return Cache::remember($cacheKey, 86400, function () use ($cveId) {
            $url = config('services.cve_api.url', 'https://services.nvd.nist.gov/rest/json/cves/2.0');
            
            $response = $this->makeRequest('GET', $url, [
                'cveId' => $cveId
            ]);
            
            if ($response && isset($response['vulnerabilities'][0])) {
                return $this->parseCVEData($response['vulnerabilities'][0]['cve']);
            }
            
            return null;
        });
    }
    
    public function searchCVE($keyword, $limit = 10)
    {
        $cacheKey = "cve_search:{$keyword}";
        
        return Cache::remember($cacheKey, 3600, function () use ($keyword, $limit) {
            $url = config('services.cve_api.url', 'https://services.nvd.nist.gov/rest/json/cves/2.0');
            
            $response = $this->makeRequest('GET', $url, [
                'keywordSearch' => $keyword,
                'resultsPerPage' => $limit
            ]);
            
            if ($response && isset($response['vulnerabilities'])) {
                return array_map(function ($item) {
                    return $this->parseCVEData($item['cve']);
                }, $response['vulnerabilities']);
            }
            
            return [];
        });
    }
    
    public function sendWebhook($url, $data, $method = 'POST')
    {
        try {
            $response = $this->makeRequest($method, $url, $data);
            Log::info('Webhook sent successfully', ['url' => $url]);
            return $response;
        } catch (\Exception $e) {
            Log::error('Webhook failed', ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }
    
    protected function makeRequest($method, $url, $data = [])
    {
        for ($i = 0; $i < $this->retries; $i++) {
            try {
                $response = Http::timeout($this->timeout)->$method($url, $data);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::warning("API request failed (attempt {$i})", [
                    'url' => $url,
                    'status' => $response->status()
                ]);
                
            } catch (\Exception $e) {
                Log::warning("API request exception (attempt {$i})", [
                    'url' => $url,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return null;
    }
    
    protected function parseCVEData($data)
    {
        return [
            'id' => $data['id'],
            'description' => $data['descriptions'][0]['value'] ?? '',
            'cvss_score' => $data['metrics']['cvssMetricV31'][0]['cvssData']['baseScore'] ?? 0,
            'severity' => $data['metrics']['cvssMetricV31'][0]['cvssData']['baseSeverity'] ?? 'UNKNOWN',
            'published' => $data['published'],
            'last_modified' => $data['lastModified'],
            'references' => array_column($data['references'], 'url')
        ];
    }
}