<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FaceRecognitionService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.face_recognition.url', env('FACE_RECOGNITION_API_URL', 'http://python-api:8000/')),
            'timeout'  => 10.0,
        ]);
    }

    public function extractEmbedding(string $imagePath)
    {
        try {
            $response = $this->client->post('extract-embedding', [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => basename($imagePath),
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            \Log::warning('Face API extract embedding error', [
                'exception' => $e->getMessage(),
                'image_path' => $imagePath
            ]);
            return ['error' => 'service_unavailable', 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            \Log::warning('Face API extract embedding general error', [
                'exception' => $e->getMessage(),
                'image_path' => $imagePath
            ]);
            return ['error' => 'general_error', 'message' => $e->getMessage()];
        }
    }

    public function verifyFace(int $eventId, string $imagePath)
    {
        try {
            $response = $this->client->post('verify-face', [
                'multipart' => [
                    [
                        'name'     => 'event_id',
                        'contents' => $eventId,
                    ],
                    [
                        'name'     => 'file',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => 'capture.jpg',
                    ],
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            \Log::warning('Face API verify error', [
                'exception' => $e->getMessage(),
                'event_id' => $eventId,
                'image_path' => $imagePath
            ]);
            return ['match' => false, 'error' => 'service_unavailable', 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            \Log::warning('Face API verify general error', [
                'exception' => $e->getMessage(),
                'event_id' => $eventId,
                'image_path' => $imagePath
            ]);
            return ['match' => false, 'error' => 'general_error', 'message' => $e->getMessage()];
        }
    }
}