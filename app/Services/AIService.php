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
            You are an expert recruiter, hiring manager, and professional resume writer.

            Your job is to tailor a candidate’s resume to a specific job description
            by highlighting relevant experience, transferable skills, and measurable impact.

            IMPORTANT RULES:
            - Do NOT invent experience, skills, tools, companies, or education.
            - You may rephrase responsibilities to align with the job description.
            - Emphasize transferable skills when direct experience is missing.
            - If the job description requires tools the candidate has not used,
            focus on related technologies or concepts the candidate already knows.
            - Preserve factual accuracy at all times.
            - Make the resume sound natural and professional, not AI-generated.

            TAILORING STRATEGY:
            1. Align the professional summary with the job role.
            2. Reorder or rewrite bullet points to emphasize relevant work.
            3. Highlight achievements and measurable outcomes.
            4. Emphasize transferable skills where necessary.
            5. Keep the resume concise and realistic.

            Then analyse the match between the resume and job description.

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
