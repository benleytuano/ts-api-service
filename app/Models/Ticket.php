<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    // Optional helpers
    public const STATUSES   = ['open','in_progress','resolved','closed'];
    public const PRIORITIES = ['low','medium','high'];

    protected $fillable = [
        'user_id',
        'assignee_id',
        'assigned_at',
        'title',
        'description',
        'category_id',
        'department_id',
        'location_id',
        'status',
        'priority',
        'contact_number',
        'patient_name',
        'equipment_details',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /* ========= Relationships ========= */

    // Requester (who created the ticket)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Current assignee (nullable)
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    /* ========= Query Scopes ========= */

    // Open/active tickets for lists
    public function scopeActive($q)
    {
        return $q->whereIn('status', ['open', 'in_progress']);
    }

    public function scopeAssignedTo($q, $user)
    {
        $id = $user instanceof User ? $user->id : $user;
        return $q->where('assignee_id', $id);
    }

    public function scopeUnassigned($q)
    {
        return $q->whereNull('assignee_id');
    }

    // Common eager-loads to avoid N+1
    public function scopeWithBasics($q)
    {
        return $q->with([
            'user:id,name,email',
            'assignee:id,name,email',
            'category:id,name',
            'department:id,name',
            'location:id,name',
        ]);
    }

    /* ========= Normalization ========= */
    protected static function booted()
    {
        static::saving(function (Ticket $t) {
            $t->status   = strtolower($t->status ?: 'open');
            $t->priority = strtolower($t->priority ?: 'medium');
        });
    }
}
