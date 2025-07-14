<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    const TYPE_ADMIN = 'admin';
    const TYPE_MEMBER = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
        'type',
        'is_system_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
            'is_system_team' => 'boolean',
        ];
    }

    /**
     * Check if this is an admin team
     */
    public function isAdminTeam(): bool
    {
        return $this->type === self::TYPE_ADMIN;
    }

    /**
     * Check if this is a member team
     */
    public function isMemberTeam(): bool
    {
        return $this->type === self::TYPE_MEMBER;
    }

    /**
     * Get the admin team
     */
    public static function adminTeam(): ?Team
    {
        return static::where('type', self::TYPE_ADMIN)
                     ->where('is_system_team', true)
                     ->first();
    }

    /**
     * Get the member team
     */
    public static function memberTeam(): ?Team
    {
        return static::where('type', self::TYPE_MEMBER)
                     ->where('is_system_team', true)
                     ->first();
    }
}
