<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'event_group',
        'attrs',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'attrs' => 'array',
        ];
    }

    /**
     * The users that belong to the event.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->withPivot('attrs')
                    ->withTimestamps()
                    ->withCasts([
                        'attrs' => 'array',
                    ]);
    }
}
