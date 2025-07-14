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
        'name',
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
        return $this;
    }

    /**
     * Remove super admin privileges
     */
    public function removeSuperAdmin(): self
    {
        $this->update(['is_super_admin' => false]);
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

        // Keep current team as member team (don't switch to admin team)
        if ($memberTeam && $this->current_team_id !== $memberTeam->id) {
            $this->switchTeam($memberTeam);
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
}
