<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'sms',
        'provider',
        'sms_opt_in',
        'email_opt_in',
        'how_did_you_hear',
        'paid_via',
        'tags',
        'is_super_admin',
        'comments',
        'topics',
        'region',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'is_admin',
        'name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'sms_opt_in' => 'boolean',
            'email_opt_in' => 'boolean',
            'tags' => 'array',
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Check if user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * Check if user is an admin (member of admin team)
     */
    public function isAdmin(): bool
    {
        $adminTeam = Team::adminTeam();
        return $adminTeam && $this->belongsToTeam($adminTeam);
    }

    /**
     * Check if user can access control panel
     */
    public function canAccessControlPanel(): bool
    {
        return $this->isAdmin() && $this->isSuperAdmin();
    }

    /**
     * Make user a super admin
     */
    public function makeSuperAdmin(): self
    {
        $this->update(['is_super_admin' => true]);
        $this->update(['super' => 1]);

        return $this;
    }

    /**
     * Remove super admin privileges
     */
    public function removeSuperAdmin(): self
    {
        $this->update(['is_super_admin' => false]);
        $this->update(['super' => false]);

        return $this;
    }

    /**
     * Add user to admin team
     */
    public function makeAdmin(): self
    {
        $adminTeam = Team::adminTeam();
        $memberTeam = Team::memberTeam();

        // Ensure user is on member team (all users should be)
        if ($memberTeam && !$this->belongsToTeam($memberTeam)) {
            $memberTeam->users()->attach($this);
        }

        // Add user to admin team
        if ($adminTeam && !$this->belongsToTeam($adminTeam)) {
            $adminTeam->users()->attach($this);
        }

        // Switch current team to admin team
        if ($adminTeam && $this->current_team_id !== $adminTeam->id) {
            $this->switchTeam($adminTeam);
        }

        return $this;
    }

    /**
     * Remove user from admin team
     */
    public function removeAdmin(): self
    {
        $adminTeam = Team::adminTeam();
        $memberTeam = Team::memberTeam();

        // Remove from admin team
        if ($adminTeam && $this->belongsToTeam($adminTeam)) {
            $adminTeam->users()->detach($this);
        }

        // Ensure user stays on member team
        if ($memberTeam && !$this->belongsToTeam($memberTeam)) {
            $memberTeam->users()->attach($this);
        }

        // Switch current team to member team
        if ($memberTeam && $this->current_team_id !== $memberTeam->id) {
            $this->switchTeam($memberTeam);
        }

        return $this;
    }

    /**
     * Get the is_admin attribute for the user.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Create a passwordless user
     */
    public static function createPasswordlessUser(string $firstName, string $lastName, ?string $email = null, ?string $sms = null): self
    {
        $userData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'password' => null, // No password for passwordless users
        ];

        if ($email) {
            $userData['email'] = $email;
            $userData['email_verified_at'] = now(); // Auto-verify since they accessed via email link
        }

        if ($sms) {
            $userData['sms'] = $sms;
        }

        $user = static::create($userData);

        // Assign user to Member team
        $memberTeam = Team::memberTeam();
        if ($memberTeam) {
            $memberTeam->users()->attach($user);
            $user->switchTeam($memberTeam);
        }

        return $user;
    }

    /**
     * Check if user uses passwordless authentication
     */
    public function isPasswordless(): bool
    {
        return is_null($this->password);
    }

    /**
     * Check if user can login via email
     */
    public function canLoginViaEmail(): bool
    {
        return !is_null($this->email);
    }

    /**
     * Check if user can login via SMS
     */
    public function canLoginViaSms(): bool
    {
        return !is_null($this->sms);
    }

    /**
     * The events that belong to the user.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_user')
                    ->withPivot('attrs')
                    ->withTimestamps()
                    ->withCasts([
                        'attrs' => 'array',
                    ]);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get the user's full name (alias for getFullNameAttribute).
     */
    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }
}
