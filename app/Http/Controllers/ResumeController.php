<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Services\AIService;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    public function create()
    {
        return Inertia::render('Create');
    }
    public function index(Request $request)
    {
        $request->validate([
            'description' => ['required','string'],
            'resume' => ['required','file','mimes:pdf']
        ]);

        $path = $request->file('resume')->store('resumes');

        $fullPath = storage_path('app/'.$path);

        $parser = new Parser();
        $pdf = $parser->parseFile($fullPath);
        $resumeText = $pdf->getText();
        $resumeText = $this->cleanPdfText($resumeText);
        $resumeText = substr($resumeText, 0, 4500);


        $jobDescription = $request->input('description');

        $ai = new AIService();

        $analysis = $ai->analyseResume(
            resumeText: $resumeText,
            jobDescription: $jobDescription
        );

        return response()->json([
            'analysis' => $analysis,
        ]);
    }
    private function cleanPdfText(string $text): string
    {
        // Replace non-breaking spaces
        $text = str_replace("\xC2\xA0", ' ', $text);

        // Remove weird unicode placeholders like \u{A0}
        $text = preg_replace('/\\\\u\{[A-F0-9]+\}/i', ' ', $text);

        // Remove strange bullet characters
        $text = preg_replace('/[▶●◀]/u', ' ', $text);

        // Normalize line breaks
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }

    public function download(Request $request)
    {
        $resume = $request->resume;

        $pdf = Pdf::loadView('resume', [
            'resume' => $resume
        ]);

        return $pdf->download('resume.pdf');
    }
}
