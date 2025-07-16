<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            'sms' => ['nullable', 'string', 'max:20'],
            'provider' => ['nullable', 'string', Rule::in(['Verizon', 'AT&T', 'T-Mobile', 'Sprint', 'Other'])],
            'sms_opt_in' => ['boolean'],
            'email_opt_in' => ['boolean'],
            'how_did_you_hear' => ['nullable', 'string', 'max:255'],
            'paid_via' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'comments' => ['nullable', 'string'],
            'topics' => ['nullable', 'string'],
            'region' => ['nullable', 'string', 'max:255'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'sms' => $input['sms'] ?? null,
                'provider' => $input['provider'] ?? null,
                'sms_opt_in' => $input['sms_opt_in'] ?? false,
                'email_opt_in' => $input['email_opt_in'] ?? false,
                'how_did_you_hear' => $input['how_did_you_hear'] ?? null,
                'paid_via' => $input['paid_via'] ?? null,
                'tags' => $input['tags'] ?? null,
                'comments' => $input['comments'] ?? null,
                'topics' => $input['topics'] ?? null,
                'region' => $input['region'] ?? null,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'sms' => $input['sms'] ?? null,
            'provider' => $input['provider'] ?? null,
            'sms_opt_in' => $input['sms_opt_in'] ?? false,
            'email_opt_in' => $input['email_opt_in'] ?? false,
            'how_did_you_hear' => $input['how_did_you_hear'] ?? null,
            'paid_via' => $input['paid_via'] ?? null,
            'tags' => $input['tags'] ?? null,
            'comments' => $input['comments'] ?? null,
            'topics' => $input['topics'] ?? null,
            'region' => $input['region'] ?? null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
