<?php

namespace App\Http\Controllers;

use App\Mail\SupportMessage;
use App\Models\User;
use App\Services\AIService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Smalot\PdfParser\Parser;

class ResumeController extends Controller
{
    public function create()
    {
        return Inertia::render('Create');
    }

    public function index(Request $request)
    {

        $request->validate([
            'description' => ['required', 'string'],
            'resume' => ['required', 'file', 'mimes:pdf'],
        ]);

        $user = auth()->user();

        if (! $user->has_credit()) {
            return response()->json([
                'message' => 'You are out of credit, buy to continue!',
                'redirect' => route('home'),
            ], 403);
        }

        $path = $request->file('resume')->store('resumes');

        $fullPath = storage_path('app/'.$path);

        $parser = new Parser;
        $pdf = $parser->parseFile($fullPath);
        $resumeText = $pdf->getText();
        $resumeText = $this->cleanPdfText($resumeText);
        $resumeText = substr($resumeText, 0, 4500);

        $jobDescription = $request->input('description');

        $ai = new AIService;

        $analysis = $ai->analyseResume(
            resumeText: $resumeText,
            jobDescription: $jobDescription
        );

        $user = User::decrement('credit');

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
            'resume' => $resume,
        ]);

        return $pdf->download('resume.pdf');
    }

    public function support()
    {
        return Inertia::render('Support');
    }

    public function support_email(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'message' => ['required', 'string', 'max:500'],
        ]);

        Mail::to('okaz692@gmail.com')->send(new SupportMessage($data));

        return redirect()->route('support')
            ->with('message', 'Message sent successfully, we aim to reply soon.');
    }
}
