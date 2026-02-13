<?php

namespace App\Services;

use OpenAI;

class AIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(config('services.openai.key'));
    }

    public function analyseResume(string $resumeText, string $jobDescription)
    {
        // dd($resumeText);
        $prompt = "
            You are an expert technical recruiter and professional resume writer.

            Analyse the resume against the job description.

            Then REWRITE and OPTIMISE the resume so it is tailored for the job.

            Extract personal details (name, email, phone, location) if present.
            If missing, return empty strings.

            Return STRICT JSON ONLY:

            {
                match_score: number,
                strengths: [],
                missing_skills: [],
                recommendations: [],
                generated_resume: {
                    name: string,
                    title: string,
                    email: string,
                    phone: string,
                    location: string,
                    summary: string,
                    skills: [],
                    experience: [
                        {
                            job_title: string,
                            company: string,
                            location: string,
                            dates: string,
                            responsibilities: []
                        }
                    ],
                    education: [
                        {
                            qualification: string,
                            institution: string,
                            dates: string
                        }
                    ]
                }
            }

            Resume:
            {$resumeText}

            Job Description:
            {$jobDescription}
            ";


            $response = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.4,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            $content = $response->choices[0]->message->content;

            return json_decode($content, true);
        }
}
