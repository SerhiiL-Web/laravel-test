<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActorRequest;
use App\Models\Actor;
use Illuminate\Http\Request;
use Inertia\Inertia;
// use OpenAI\Laravel\Facades\OpenAI;

class ActorController extends Controller
{
    public function create()
    {
        return Inertia::render('Actors/Create');
    }

    public function store(StoreActorRequest $request)
    {
        $validated = $request->validated();

        // Process description with OpenAI API
        $extractedData = $this->processWithOpenAI($validated['description']);

        // Check if required fields are present
        if (empty($extractedData['first_name']) || empty($extractedData['last_name']) || empty($extractedData['address'])) {
            return back()->withErrors([
                'description' => 'Please add first name, last name, and address to your description.'
            ]);
        }

        // Create actor record
        $actor = Actor::create([
            'email' => $validated['email'],
            'description' => $validated['description'],
            'first_name' => $extractedData['first_name'],
            'last_name' => $extractedData['last_name'],
            'address' => $extractedData['address'],
            'height' => $extractedData['height'] ?? null,
            'weight' => $extractedData['weight'] ?? null,
            'gender' => $extractedData['gender'] ?? null,
            'age' => $extractedData['age'] ?? null,
        ]);

        return redirect()->route('home')->with('success', 'Actor information submitted successfully!');
    }

    public function index()
    {
        $actors = Actor::select('first_name', 'address', 'gender', 'height')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Actors/Index', [
            'actors' => $actors
        ]);
    }

    public function promptValidation()
    {
        return response()->json([
            'message' => 'text_prompt'
        ]);
    }

    private function processWithOpenAI(string $description): array
    {
        try {
            // Check if OpenAI package is available
            if (!class_exists('OpenAI\Laravel\Facades\OpenAI')) {
                // Fallback: simple regex-based extraction for demo purposes
                return $this->extractDataWithRegex($description);
            }

            $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Extract the following information from the given text and return it as JSON: First Name, Last Name, Address, Height, Weight, Gender, Age. If any information is not available, use null for that field.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $description
                    ]
                ],
                'temperature' => 0.1,
            ]);

            $content = $response->choices[0]->message->content;
            $data = json_decode($content, true);

            return $data ?: [];
        } catch (\Exception $e) {
            // Fallback: return empty array if OpenAI fails
            return [];
        }
    }

    private function extractDataWithRegex(string $description): array
    {
        // Simple regex-based extraction for demo purposes
        $data = [];
        
        // Extract first and last name (basic pattern)
        if (preg_match('/([A-Z][a-z]+)\s+([A-Z][a-z]+)/', $description, $matches)) {
            $data['first_name'] = $matches[1];
            $data['last_name'] = $matches[2];
        }
        
        // Extract address (look for street patterns)
        if (preg_match('/(\d+\s+[A-Za-z\s]+(?:Street|St|Avenue|Ave|Road|Rd|Drive|Dr|Lane|Ln|Boulevard|Blvd))/i', $description, $matches)) {
            $data['address'] = $matches[1];
        }
        
        // Extract age
        if (preg_match('/(\d+)[\s-]*(?:year|yr)s?\s*old/i', $description, $matches)) {
            $data['age'] = (int) $matches[1];
        }
        
        // Extract gender
        if (preg_match('/\b(male|female|man|woman)\b/i', $description, $matches)) {
            $data['gender'] = strtolower($matches[1]);
        }
        
        // Extract height
        if (preg_match('/(\d+)\s*(?:feet|ft|foot|inches|in|cm|centimeters?)/i', $description, $matches)) {
            $data['height'] = $matches[1] . (strpos($description, 'cm') !== false ? ' cm' : ' ft');
        }
        
        // Extract weight
        if (preg_match('/(\d+)\s*(?:pounds|lbs|kg|kilograms?)/i', $description, $matches)) {
            $data['weight'] = $matches[1] . (strpos($description, 'kg') !== false ? ' kg' : ' lbs');
        }
        
        return $data;
    }
}
