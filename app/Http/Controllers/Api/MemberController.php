<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of members.
     */
    public function index(): JsonResponse
    {
        $members = User::select([
            'id', 'first_name', 'last_name','email', 'sms', 'provider', 'tags',
            'sms_opt_in', 'email_opt_in', 'how_did_you_hear', 'paid_via',
            'created_at', 'updated_at'
        ])->paginate(1000);

        return response()->json($members->items());
    }

    /**
     * Store a newly created member.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'sms' => 'nullable|string|max:20',
            'provider' => 'nullable|string|max:50',
            'tags' => 'nullable|string',
            'sms_opt_in' => 'boolean',
            'email_opt_in' => 'boolean',
            'how_did_you_hear' => 'nullable|string|max:500',
            'paid_via' => 'nullable|string|max:100',
        ]);

        // Set default password for CSV imports
        $validated['password'] = bcrypt('DefaultPassword123!'); // Default password
        $validated['sms_opt_in'] = $validated['sms_opt_in'] ?? true;
        $validated['email_opt_in'] = $validated['email_opt_in'] ?? true;

        // Convert tags to array if it's a string
        if (isset($validated['tags']) && is_string($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        $member = User::create($validated);

        // Assign user to Member team
        $this->assignToMemberTeam($member);

        return response()->json($member, 201);
    }

    /**
     * Display the specified member.
     */
    public function show(User $member): JsonResponse
    {
        return response()->json($member);
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, User $member): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($member->id)],
            'sms' => 'nullable|string|max:20',
            'provider' => 'nullable|string|max:50',
            'tags' => 'nullable|string',
            'sms_opt_in' => 'boolean',
            'email_opt_in' => 'boolean',
            'how_did_you_hear' => 'nullable|string|max:500',
            'paid_via' => 'nullable|string|max:100',
        ]);

        // Convert tags to array if it's a string
        if (isset($validated['tags']) && is_string($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        $member->update($validated);

        return response()->json($member);
    }

    /**
     * Remove the specified member.
     */
    public function destroy(User $member): JsonResponse
    {
        $member->delete();

        return response()->json(['message' => 'Member deleted successfully']);
    }

    /**
     * Bulk import members from CSV.
     */
    public function bulkImport(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'members' => 'required|array',
            'members.*.name' => 'required|string|max:255',
            'members.*.email' => 'required|email|distinct',
            'members.*.sms' => 'nullable|string|max:20',
            'members.*.provider' => 'nullable|string|max:50',
            'members.*.tags' => 'nullable|string',
            'members.*.sms_opt_in' => 'nullable|boolean',
            'members.*.email_opt_in' => 'nullable|boolean',
            'members.*.how_did_you_hear' => 'nullable|string|max:500',
            'members.*.paid_via' => 'nullable|string|max:100',
        ]);

        $created = [];
        $errors = [];

        foreach ($validated['members'] as $index => $memberData) {
            try {
                // Check if email already exists
                if (User::where('email', $memberData['email'])->exists()) {
                    $errors[] = [
                        'index' => $index,
                        'email' => $memberData['email'],
                        'error' => 'Email already exists'
                    ];
                    continue;
                }

                $memberData['password'] = bcrypt('DefaultPassword123!');
                $memberData['sms_opt_in'] = $memberData['sms_opt_in'] ?? true;
                $memberData['email_opt_in'] = $memberData['email_opt_in'] ?? true;

                // Convert tags to array if it's a string
                if (isset($memberData['tags']) && is_string($memberData['tags'])) {
                    $memberData['tags'] = array_map('trim', explode(',', $memberData['tags']));
                }

                $member = User::create($memberData);

                // Assign user to Member team
                $this->assignToMemberTeam($member);

                $created[] = $member;
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'email' => $memberData['email'] ?? 'unknown',
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'created' => count($created),
            'errors' => $errors,
            'members' => $created
        ]);
    }

    /**
     * Assign user to the Member team and set it as current team.
     */
    protected function assignToMemberTeam(User $user): void
    {
        $memberTeam = Team::memberTeam();
        if ($memberTeam) {
            $memberTeam->users()->attach($user);
            $user->switchTeam($memberTeam);
        }
    }
}
