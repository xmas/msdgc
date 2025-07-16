<?php

namespace App\Http\Controllers;

use App\Models\PasswordlessLoginToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordlessLoginNotification;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PasswordlessLoginController extends Controller
{
    /**
     * Show the passwordless login form
     */
    public function show()
    {
        return Inertia::render('Auth/PasswordlessLogin', [
            'status' => session('message'),
        ]);
    }

    /**
     * Send the passwordless login link
     */
    public function sendLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'type' => 'required|in:email,sms'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $identifier = $request->identifier;
        $type = $request->type;

        // Validate format based on type
        if ($type === 'email' && !filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages([
                'identifier' => 'Please enter a valid email address.'
            ]);
        }

        if ($type === 'sms' && !preg_match('/^\+?[\d\s\-\(\)]+$/', $identifier)) {
            throw ValidationException::withMessages([
                'identifier' => 'Please enter a valid phone number.'
            ]);
        }

        // Generate token
        $token = PasswordlessLoginToken::generateToken($identifier, $type);

        // Send the link
        if ($type === 'email') {
            $this->sendEmailLink($identifier, $token);
        } else {
            $this->sendSmsLink($identifier, $token);
        }

        return back()->with('message',
            $type === 'email'
                ? 'We\'ve sent you a login link at ' . $identifier
                : 'We\'ve sent you a login link via SMS to ' . $identifier
        );
    }

    /**
     * Resend the passwordless login link
     */
    public function resendLink(Request $request)
    {
        // This is essentially the same as sendLink but with a different success message
        return $this->sendLink($request)->with('message', 'Login link has been resent.');
    }

    /**
     * Process the passwordless login
     */
    public function login(Request $request, $token)
    {
        $loginToken = PasswordlessLoginToken::where('token', $token)
            ->valid()
            ->first();

        if (!$loginToken) {
            return redirect()->route('passwordless.show')
                ->withErrors(['token' => 'This login link is invalid or has expired.']);
        }

        // Check if user exists
        $user = $this->findExistingUser($loginToken->identifier, $loginToken->type);

        if (!$user) {
            // User doesn't exist, redirect to name collection
            return redirect()->route('passwordless.collect-name', ['token' => $token]);
        }

        // Mark token as used
        $loginToken->markAsUsed();

        // Log the user in
        Auth::login($user, true);

        // Redirect to intended or dashboard
        return redirect()->intended(config('fortify.home', '/dashboard'));
    }

    /**
     * Show name collection form for new users
     */
    public function collectName(Request $request, $token)
    {
        $loginToken = PasswordlessLoginToken::where('token', $token)
            ->valid()
            ->first();

        if (!$loginToken) {
            return redirect()->route('passwordless.show')
                ->withErrors(['token' => 'This login link is invalid or has expired.']);
        }

        return Inertia::render('Auth/CollectName', [
            'token' => $token,
            'identifier' => $loginToken->identifier,
            'type' => $loginToken->type
        ]);
    }

    /**
     * Process name collection and complete registration
     */
    public function completeName(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $loginToken = PasswordlessLoginToken::where('token', $token)
            ->valid()
            ->first();

        if (!$loginToken) {
            return redirect()->route('passwordless.show')
                ->withErrors(['token' => 'This login link is invalid or has expired.']);
        }

        // Mark token as used
        $loginToken->markAsUsed();

        // Create user with the provided name
        if ($loginToken->type === 'email') {
            $user = User::createPasswordlessUser($request->first_name, $request->last_name, $loginToken->identifier, null);
        } else {
            $user = User::createPasswordlessUser($request->first_name, $request->last_name, $loginToken->identifier);
        }

        // Log the user in
        Auth::login($user, true);

        return redirect()->intended(config('fortify.home', '/dashboard'));
    }

    /**
     * Find existing user based on identifier
     */
    private function findExistingUser(string $identifier, string $type): ?User
    {
        if ($type === 'email') {
            return User::where('email', $identifier)->first();
        } else {
            return User::where('sms', $identifier)->first();
        }
    }

    /**
     * Find or create user based on identifier
     */
    private function findOrCreateUser(string $identifier, string $type): User
    {
        $user = $this->findExistingUser($identifier, $type);

        if ($user) {
            return $user;
        }

        // User doesn't exist, we need to collect their name first
        // This should redirect to name collection, but for now return null
        // The calling method should handle this case
        throw new \Exception('User not found and automatic creation not supported in this context');
    }

    /**
     * Send email with login link
     */
    private function sendEmailLink(string $email, PasswordlessLoginToken $token): void
    {
        $loginUrl = route('passwordless.login', ['token' => $token->token]);

        try {
            Notification::route('mail', $email)
                ->notify(new PasswordlessLoginNotification($loginUrl, $email));
        } catch (\Exception $e) {
            Log::error('Failed to send passwordless login email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            // Re-throw for now, but you might want to handle this differently
            throw $e;
        }
    }

    /**
     * Send SMS with login link
     */
    private function sendSmsLink(string $phone, PasswordlessLoginToken $token): void
    {
        $loginUrl = route('passwordless.login', ['token' => $token->token]);

        // For now, we'll just log the SMS. In production, you'd integrate with
        // a service like Twilio, AWS SNS, or similar
        Log::info('SMS Login Link', [
            'phone' => $phone,
            'url' => $loginUrl,
            'message' => "Your login link: {$loginUrl}"
        ]);

        // TODO: Implement actual SMS sending
        // Example with Twilio:
        // $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        // $twilio->messages->create($phone, [
        //     'from' => config('services.twilio.from'),
        //     'body' => "Your login link: {$loginUrl}"
        // ]);
    }
}
