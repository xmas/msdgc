<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Show the messaging form
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'sms', 'provider', 'sms_opt_in', 'email_opt_in', 'tags')
            ->get();

        $allTags = User::whereNotNull('tags')
            ->get()
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->values()
            ->sort();

        // Get recent message history
        $recentMessages = \App\Models\Message::with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Messages/Send', [
            'users' => $users,
            'availableTags' => $allTags,
            'recentMessages' => $recentMessages
        ]);
    }

    /**
     * Send messages to users
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|string|in:all,tags',
            'selectedTags' => 'array',
            'selectedTags.*' => 'string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $query = User::query();

        // Filter recipients based on selection
        if ($request->recipients === 'tags' && !empty($request->selectedTags)) {
            $query->where(function ($q) use ($request) {
                foreach ($request->selectedTags as $tag) {
                    $q->orWhereJsonContains('tags', $tag);
                }
            });
        }

        $users = $query->get();

        $emailCount = 0;
        $smsCount = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                // Send email if user opted in
                if ($user->email_opt_in && $user->email) {
                    $this->sendEmail($user, $request->subject, $request->message);
                    $emailCount++;
                }

                // Send SMS if user opted in
                if ($user->sms_opt_in && $user->sms && $user->provider) {
                    $this->sendSMS($user, $request->message);
                    $smsCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed to send message to {$user->name}: " . $e->getMessage();
                Log::error("Message send error for user {$user->id}: " . $e->getMessage());
            }
        }

        // Store message history
        \App\Models\Message::create([
            'sender_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'recipients_type' => $request->recipients,
            'selected_tags' => $request->selectedTags ?? [],
            'email_count' => $emailCount,
            'sms_count' => $smsCount,
            'errors' => !empty($errors) ? json_encode($errors) : null,
        ]);

        $message = "Successfully sent {$emailCount} emails and {$smsCount} SMS messages.";

        if (!empty($errors)) {
            $message .= " Some messages failed to send.";
        }

        return back()->with('success', $message);
    }

    /**
     * Send email to user
     */
    private function sendEmail($user, $subject, $message)
    {
        Mail::raw($message, function ($mail) use ($user, $subject) {
            $mail->to($user->email, $user->name)
                 ->subject($subject);
        });
    }

    /**
     * Send SMS to user
     */
    private function sendSMS($user, $message)
    {
        // Clean phone number (remove spaces and non-numeric characters)
        $cleanNumber = preg_replace('/[^0-9]/', '', $user->sms);

        // Get SMS gateway based on provider
        $smsGateway = $this->getSMSGateway($user->provider);

        if (!$smsGateway) {
            throw new \Exception("Unknown SMS provider: {$user->provider}");
        }

        $smsEmail = $cleanNumber . '@' . $smsGateway;

        Mail::raw($message, function ($mail) use ($smsEmail) {
            $mail->to($smsEmail)
                 ->subject(''); // SMS gateways often ignore subject
        });
    }

    /**
     * Get SMS gateway domain based on provider
     */
    private function getSMSGateway($provider)
    {
        $gateways = [
            'Verizon' => 'vtext.com',
            'AT&T' => 'txt.att.net',
            'T-Mobile' => 'tmomail.net',
            'Sprint' => 'messaging.sprintpcs.com',
            'Other' => 'vtext.com', // Default fallback
        ];

        return $gateways[$provider] ?? 'vtext.com';
    }
}
