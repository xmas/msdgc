<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordlessLoginToken extends Model
{
    protected $fillable = [
        'token',
        'identifier',
        'type',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime'
    ];

    /**
     * Generate a new passwordless login token
     */
    public static function generateToken(string $identifier, string $type, int $expirationMinutes = 15): self
    {
        // Clean up old tokens for this identifier
        static::where('identifier', $identifier)
              ->where('type', $type)
              ->delete();

        return static::create([
            'token' => Str::random(64),
            'identifier' => $identifier,
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes($expirationMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * Check if token is valid and not expired
     */
    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => Carbon::now()]);
    }

    /**
     * Scope for valid tokens
     */
    public function scopeValid($query)
    {
        return $query->whereNull('used_at')
                    ->where('expires_at', '>', Carbon::now());
    }
}
