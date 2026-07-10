<?php

namespace App\Http\Controllers;

use App\Models\IdCard;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class IdCardController extends Controller
{
    /**
     * Enforce access control for Developer, Admin, and Teacher roles.
     */
    protected function checkAccess()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->users_level, [0, 1, 2])) {
            abort(403, 'Unauthorized access to ID number generation.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAccess();

        $query = IdCard::query();

        // Search name or ID number
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $idCards = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('id-cards.index', compact('idCards'));
    }

    public function create()
    {
        $this->checkAccess();
        return view('id-cards.create');
    }

    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'permanent_address' => 'required|string',
            'current_address' => 'nullable|string',
            'gender' => 'required|string|in:Male,Female,Other',
            'age' => 'required|integer|min:0|max:150',
            'id_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validity_date' => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('+1 year')),
            'status' => 'required|string|in:new,renew,archived,graduated,dismissed,deceased',
            'class' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255',
            'student_phone' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
        ]);

        if ($request->hasFile('id_picture')) {
            $image = $request->file('id_picture');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/id_pictures');
            
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            $image->move($destinationPath, $filename);
            $validated['id_picture'] = 'id_pictures/' . $filename;
        }

        $idCard = IdCard::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Generate ID Card',
            'description' => "Generated ID card (Number: {$idCard->id_number}) for student {$idCard->full_name}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('id-cards.index')->with('success', "ID Card for {$idCard->full_name} generated successfully. ID: {$idCard->id_number}");
    }

    public function show(IdCard $idCard)
    {
        $this->checkAccess();
        return view('id-cards.show', compact('idCard'));
    }

    public function edit(IdCard $idCard)
    {
        $this->checkAccess();
        return view('id-cards.edit', compact('idCard'));
    }

    public function update(Request $request, IdCard $idCard)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'permanent_address' => 'required|string',
            'current_address' => 'nullable|string',
            'gender' => 'required|string|in:Male,Female,Other',
            'age' => 'required|integer|min:0|max:150',
            'id_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validity_date' => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('+1 year')),
            'status' => 'required|string|in:new,renew,archived,graduated,dismissed,deceased',
            'class' => 'required|string|max:255',
            'batch_number' => 'required|string|max:255',
            'student_phone' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
        ]);

        if ($request->hasFile('id_picture')) {
            // Delete old picture if exists
            if ($idCard->id_picture && File::exists(public_path($idCard->id_picture))) {
                File::delete(public_path($idCard->id_picture));
            }

            $image = $request->file('id_picture');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/id_pictures');
            
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            $image->move($destinationPath, $filename);
            $validated['id_picture'] = 'id_pictures/' . $filename;
        } elseif ($request->input('remove_picture') === '1') {
            if ($idCard->id_picture && File::exists(public_path($idCard->id_picture))) {
                File::delete(public_path($idCard->id_picture));
            }
            $validated['id_picture'] = null;
        }

        $idCard->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Update ID Card',
            'description' => "Updated details of ID Card (Number: {$idCard->id_number}) for student {$idCard->full_name}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('id-cards.index')->with('success', "ID Card details for {$idCard->full_name} updated successfully.");
    }

    public function destroy(Request $request, IdCard $idCard)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->users_level, [0, 1])) {
            abort(403, 'Unauthorized. Only Developers and Admins can delete ID Cards.');
        }

        // Delete picture
        if ($idCard->id_picture && File::exists(public_path($idCard->id_picture))) {
            File::delete(public_path($idCard->id_picture));
        }

        $fullName = $idCard->full_name;
        $idNum = $idCard->id_number;
        $idCard->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'action' => 'Delete ID Card',
            'description' => "Deleted ID Card (Number: {$idNum}) belonging to {$fullName}.",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('id-cards.index')->with('success', "ID Card for {$fullName} deleted successfully.");
    }
}
