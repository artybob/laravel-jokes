<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Joke;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchJokesCommand extends Command
{
    protected $signature = 'jokes:fetch';
    protected $description = 'Fetch jokes from API and store in database';

    private array $apis = [
        'https://official-joke-api.appspot.com/random_joke',
        'https://official-joke-api.appspot.com/jokes/programming/random',
        'https://v2.jokeapi.dev/joke/Any?safe-mode'
    ];

    public function handle()
    {
        try {
            $apiUrl = $this->apis[array_rand($this->apis)];

            $response = Http::timeout(10)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                $this->storeJoke($data, $apiUrl);
                $this->info('Joke fetched and stored successfully!');
            } else {
                $this->error('Failed to fetch joke from API');
            }

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Joke fetch error: ' . $e->getMessage());
        }
    }

    private function storeJoke(array $data, string $apiUrl): void
    {
        $jokeData = [
            'api_id' => $data['id'] ?? null,
            'type' => $this->extractType($data, $apiUrl),
            'setup' => $data['setup'] ?? $data['joke'] ?? null,
            'punchline' => $data['punchline'] ?? null,
            'joke' => $data['joke'] ?? $data['setup'] ?? null,
            'raw_data' => $data
        ];

        if (isset($data['joke']) && !isset($data['setup'])) {
            $jokeData['setup'] = $data['joke'];
            $jokeData['type'] = $data['type'] ?? 'single';
        }

        if (isset($data['category'])) {
            $jokeData['type'] = $data['category'];
        }

        Joke::create($jokeData);
    }

    private function extractType(array $data, string $apiUrl): string
    {
        if (isset($data['type'])) {
            return $data['type'];
        }

        if (strpos($apiUrl, 'programming') !== false) {
            return 'programming';
        }

        return 'general';
    }
}
