<?php

namespace App\Http\Controllers\User;

use App\Enums\ComplaintStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintFile;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $complaints = Complaint::with('person')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('user.complaints.index', compact('complaints'));
    }

    public function create(): View
    {
        return view('user.complaints.create', [
            'persons' => Person::orderBy('name')->get(),
            'directory' => [
                'approvers' => User::where('role', UserRole::Approver)->where('is_active', true)->get(),
                'lawyers' => User::where('role', UserRole::Lawyer)->where('is_active', true)->get(),
                'counselors' => User::where('role', UserRole::Counselor)->where('is_active', true)->get(),
                'law_enforcers' => User::where('role', UserRole::LawEnforcer)->where('is_active', true)->get(),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'details' => ['required', 'string'],
            'is_anonymous' => ['nullable', 'boolean'],
            'person_mode' => ['required', 'in:existing,new'],
            'person_id' => ['required_if:person_mode,existing', 'nullable', 'exists:persons,id'],
            'person_name' => ['required_if:person_mode,new', 'nullable', 'string', 'max:255'],
            'person_description' => ['nullable', 'string'],
            'person_image' => ['nullable', 'image', 'max:5120'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:51200', 'mimetypes:image/jpeg,image/png,image/webp,image/gif,audio/mpeg,audio/wav,audio/mp4,video/mp4,video/quicktime,video/webm'],
        ]);

        $complaint = DB::transaction(function () use ($request, $data) {
            if ($data['person_mode'] === 'existing') {
                $person = Person::findOrFail($data['person_id']);
            } else {
                $imagePath = null;
                if ($request->hasFile('person_image')) {
                    $imagePath = $request->file('person_image')->store('persons', 'public');
                }

                $person = Person::create([
                    'name' => $data['person_name'],
                    'description' => $data['person_description'] ?? null,
                    'image_path' => $imagePath,
                    'created_by' => $request->user()->id,
                ]);
            }

            $complaint = Complaint::create([
                'user_id' => $request->user()->id,
                'person_id' => $person->id,
                'title' => $data['title'],
                'details' => $data['details'],
                'is_anonymous' => $request->boolean('is_anonymous'),
                'status' => ComplaintStatus::Pending,
            ]);

            foreach ($request->file('files', []) as $file) {
                $mime = $file->getMimeType() ?? '';
                $type = str_starts_with($mime, 'image/') ? 'image'
                    : (str_starts_with($mime, 'audio/') ? 'audio' : 'video');

                ComplaintFile::create([
                    'complaint_id' => $complaint->id,
                    'path' => $file->store('complaints/'.$complaint->id, 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $mime,
                    'type' => $type,
                ]);
            }

            return $complaint;
        });

        return redirect()
            ->route('user.complaints.show', $complaint)
            ->with('status', 'Complaint submitted for approval.');
    }

    public function show(Request $request, Complaint $complaint): View
    {
        abort_unless($complaint->user_id === $request->user()->id, 403);
        $complaint->load(['person', 'files', 'approvals']);

        return view('user.complaints.show', compact('complaint'));
    }

    public function directory(): View
    {
        return view('user.directory', [
            'approvers' => User::where('role', UserRole::Approver)->where('is_active', true)->get(),
            'lawyers' => User::where('role', UserRole::Lawyer)->where('is_active', true)->get(),
            'counselors' => User::where('role', UserRole::Counselor)->where('is_active', true)->get(),
            'law_enforcers' => User::where('role', UserRole::LawEnforcer)->where('is_active', true)->get(),
        ]);
    }
}
