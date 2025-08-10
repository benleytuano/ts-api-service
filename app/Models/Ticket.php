<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    // Ticket belongs to a user (requester)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ticket belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Ticket belongs to a department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Ticket belongs to a location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
