<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ticket;

class TicketService
{

    public function create(array $payload)
    {
        return Ticket::create($payload);
    }

    public function getVisibleTicketsFor(User $user)
    {
        // Include all related details to avoid N+1 queries
        $q = Ticket::query()->with(['user', 'category', 'department', 'location']);

        if ($user->isRole('admin')) {
            return $q->latest()->get();
        }

        if ($user->isRole('agent')) {
            return $q->where('status', 'active')
                    ->latest()
                    ->get();
        }

        return $q->where('user_id', $user->id)
                ->latest()
                ->get();
    }
    
    public function getById($id)
    {
        return Ticket::findOrFail($id);
    }


}