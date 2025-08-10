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
        $q = Ticket::query();

        if ($user->isRole('admin')) {
            return $q->latest()->get();
        }

        if ($user->isRole('agent')) {
            return $q->where('status', 'active') // all active tickets, no department filter
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