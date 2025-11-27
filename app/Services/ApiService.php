<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = url('/api');
        $this->token = session('api_token') ?? null;
    }

    public function setToken($token)
    {
        $this->token = $token;
        session(['api_token' => $token]);
    }

    protected function request($method, $endpoint, $data = [])
    {
        $response = Http::withHeaders([
            'Authorization' => $this->token ? 'Bearer ' . $this->token : null,
            'Accept' => 'application/json',
        ])->{strtolower($method)}($this->baseUrl . $endpoint, $data);

        return $response->json();
    }

    public function get($endpoint) { return $this->request('GET', $endpoint); }
    public function post($endpoint, $data = []) { return $this->request('POST', $endpoint, $data); }
    public function put($endpoint, $data = []) { return $this->request('PUT', $endpoint, $data); }
    public function delete($endpoint) { return $this->request('DELETE', $endpoint); }
}








