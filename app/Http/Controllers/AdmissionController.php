<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionController extends Controller
{
    /**
     * Show selection introductory page.
     */
    public function selection()
    {
        return view('admission.selection');
    }

    /**
     * Show assessment page.
     */
    public function assessment()
    {
        $questions = \App\Models\AssessmentQuestion::inRandomOrder()->take(5)->get();
        return view('admission.assessment', compact('questions'));
    }

    /**
     * Evaluate assessment answers and qualify enrollee.
     */
    public function submitAssessment(Request $request)
    {
        $request->validate([
            'answers' => 'required|array|size:5',
            'answers.*' => 'required|string|in:A,B,C,D',
        ]);

        $answers = $request->input('answers', []);
        $questionIds = array_keys($answers);
        $questions = \App\Models\AssessmentQuestion::whereIn('id', $questionIds)->get();

        $score = 0;
        foreach ($questions as $q) {
            if (($answers[$q->id] ?? '') === $q->correct_option) {
                $score++;
            }
        }

        // Generate enrollment number regardless of score to determine enrollees' knowledge
        $year = date('Y');
        $randomNum = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $enrollmentNumber = "ENR-{$year}-{$randomNum}";

        // Keep in session to pre-fill the form
        session([
            'enrollment_number' => $enrollmentNumber,
            'score' => $score,
            'assessment_passed' => true
        ]);

        return redirect()->route('admission.form')->with('success', 'Assessment completed. Please submit your admission details below.');
    }

    /**
     * Show the admission form.
     */
    public function showForm()
    {
        if (!session('assessment_passed') || !session('enrollment_number')) {
            return redirect()->route('admission.selection')->with('error', 'Please complete and pass the assessment first.');
        }

        return view('admission.form');
    }

    /**
     * Save enrollee profile and check off requirements.
     */
    public function submitAdmission(Request $request)
    {
        $request->validate([
            'enrollment_number' => 'required|string|unique:admissions,enrollment_number',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'course' => 'required|string|max:255',
            'file_good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_birth_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_picture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $requirements = [];
        foreach (['good_moral', 'birth_cert', 'card', 'picture'] as $req) {
            $fileKey = 'file_' . $req;
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $requirements[$req] = $file->store('admissions/credentials', 'public');
            } else {
                $requirements[$req] = $request->has('req_' . $req) ? 'Submitted' : null;
            }
        }

        $admission = Admission::create([
            'enrollment_number' => $request->input('enrollment_number'),
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'course' => $request->input('course'),
            'score' => session('score', 4),
            'requirements' => $requirements,
            'status' => 'pending',
        ]);

        // Clear assessment sessions
        session()->forget(['enrollment_number', 'score', 'assessment_passed']);

        return redirect()->route('admission.confirmation', $admission)->with('success', 'Admission details submitted successfully!');
    }

    /**
     * Render admission confirmation screen.
     */
    public function confirmation(Admission $admission)
    {
        return view('admission.confirmation', compact('admission'));
    }

    /**
     * Admin view list of admissions.
     */
    public function adminIndex()
    {
        $user = Auth::user();
        if (!$user || !($user->users_level === 0 || $user->users_level === 1 || ($user->users_level === 2 && $user->can_manage_admissions))) {
            abort(403, 'Unauthorized action.');
        }

        $admissions = Admission::latest()->get();

        return view('admission.admin-index', compact('admissions'));
    }

    /**
     * Admin update admission status.
     */
    public function adminUpdate(Admission $admission, $status)
    {
        $user = Auth::user();
        if (!$user || !($user->users_level === 0 || $user->users_level === 1 || ($user->users_level === 2 && $user->can_manage_admissions))) {
            abort(403, 'Unauthorized action.');
        }

        if (in_array($status, ['approved', 'rejected', 'pending'])) {
            $admission->update(['status' => $status]);
        }

        return redirect()->back()->with('success', "Admission status updated to {$status}!");
    }

    /**
     * Admin update admission requirements checklist.
     */
    public function adminUpdateRequirements(Request $request, Admission $admission)
    {
        $user = Auth::user();
        if (!$user || !($user->users_level === 0 || $user->users_level === 1 || ($user->users_level === 2 && $user->can_manage_admissions))) {
            abort(403, 'Unauthorized action.');
        }

        $inputReqs = $request->input('requirements', []);
        
        $requirements = [
            'good_moral' => isset($inputReqs['good_moral']),
            'birth_cert' => isset($inputReqs['birth_cert']),
            'card' => isset($inputReqs['card']),
            'picture' => isset($inputReqs['picture']),
        ];

        $admission->update(['requirements' => $requirements]);

        return redirect()->back()->with('success', 'Admission requirements updated successfully!');
    }

    /**
     * Admin update all fields of admission.
     */
    public function adminUpdateAll(Request $request, Admission $admission)
    {
        $user = Auth::user();
        if (!$user || !($user->users_level === 0 || $user->users_level === 1 || ($user->users_level === 2 && $user->can_manage_admissions))) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'score' => 'required|integer|min:0|max:5',
            'file_good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_birth_cert' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'file_picture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $requirements = $admission->requirements ?? [];

        foreach (['good_moral', 'birth_cert', 'card', 'picture'] as $req) {
            $fileKey = 'file_' . $req;
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $requirements[$req] = $file->store('admissions/credentials', 'public');
            }
        }

        $admission->update([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'score' => $request->input('score'),
            'requirements' => $requirements,
        ]);

        return redirect()->back()->with('success', 'Admission record updated successfully!');
    }
}
