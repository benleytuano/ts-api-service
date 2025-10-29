<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketUpdate extends Model
{
    use HasFactory;

    // Update types
    public const TYPE_COMMENT = 'comment';
    public const TYPE_STATUS_CHANGE = 'status_change';
    public const TYPE_ASSIGNMENT = 'assignment';
    public const TYPE_INTERNAL_NOTE = 'internal_note';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'type',
        'is_internal',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /* ========= Relationships ========= */

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ========= Query Scopes ========= */

    // Only public updates (visible to ticket creator)
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    // Only internal notes (visible to agents/admins only)
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    // Filter by type
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Common eager-loads
    public function scopeWithUser($query)
    {
        return $query->with('user:id,first_name,last_name,email');
    }
}

