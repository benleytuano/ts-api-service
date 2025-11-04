<?php

namespace App\Services;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketUpdate;

class TicketService
{

    public function create(array $payload)
    {
        return Ticket::create($payload);
    }

    public function getVisibleTicketsFor(User $user)
    {
        // Include all related details to avoid N+1 queries
        $q = Ticket::query()->with(['user','assignee', 'category', 'department', 'location']);

        if ($user->isRole('admin')) {
            return $q->latest()->get();
        }

        if ($user->isRole('agent')) {
            return $q->latest()->get();
        }

        return $q->where('user_id', $user->id)
                ->latest()
                ->get();
    }
    
    public function getById($id)
    {
        return Ticket::findOrFail($id);
    }


    public function assign(Ticket $ticket, User $actor): Ticket
    {
        if (!($actor->isRole('admin') || $actor->isRole('agent'))) {
            abort(403, 'Not allowed to assign tickets.');
        }

        // Atomic claim: only update if currently unassigned
        $changed = Ticket::whereKey($ticket->id)
            ->whereNull('assignee_id')
            ->update([
                'assignee_id' => $actor->id,
                'assigned_at' => now(),
            ]);

        if (!$changed) {
            abort(409, 'Ticket already assigned. Refresh and try again.');
        }

        return $ticket->fresh(['user','assignee','category','department','location']);
    }

    public function reassign(Ticket $ticket, int $assigneeId, User $actor): Ticket
    {
        if (!$actor->isRole('admin')) {
            abort(403, 'Only admins can reassign.');
        }

        $ticket->forceFill([
            'assignee_id' => $assigneeId,
            'assigned_at' => now(),
        ])->save();

        return $ticket->fresh(['user','assignee','category','department','location']);
    }

    public function unassign(Ticket $ticket, User $actor): Ticket
    {
        if (!($actor->isRole('admin') || $actor->isRole('agent'))) {
            abort(403, 'Not allowed to unassign.');
        }

        // Guard against races (optional): only unassign if who we think still owns it
        $query = Ticket::whereKey($ticket->id);
        if (!is_null($actor)) {
            $query->where('assignee_id', $actor->id);
        }

        $changed = $query->update(['assignee_id' => null, 'assigned_at' => null]);

        if (!$changed) {
            abort(409, 'Assignment changed. Refresh and try again.');
        }

        return $ticket->fresh(['user','assignee','category','department','location']);
    }

    public function resolve(Ticket $ticket, User $actor): Ticket
    {
        // Only the current assignee can resolve
        if ($ticket->assignee_id !== $actor->id) {
            abort(403, 'Only the current assignee can resolve this ticket.');
        }

        // Must be in a resolvable state
        if (in_array($ticket->status, ['resolved', 'closed'], true)) {
            abort(409, 'Ticket is already resolved/closed.');
        }

        // Optional: require assignment first (explicit message)
        if (is_null($ticket->assignee_id)) {
            abort(409, 'Ticket is unassigned. Assign it before resolving.');
        }

        // Atomic state change to avoid race conditions
        $changed = Ticket::whereKey($ticket->id)
            ->where('assignee_id', $actor->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->update([
                'status' => 'resolved',
            ]);

        if (!$changed) {
            abort(409, 'Ticket state changed. Refresh and try again.');
        }

        // Return fresh with relationships (keeps your response shape consistent)
        return $ticket->fresh(['user','assignee','category','department','location']);
    }

    /* ========= Ticket Updates ========= */

    /**
     * Get all updates for a ticket (filtered by user role)
     */
    public function getUpdatesForTicket(Ticket $ticket, User $user)
    {
        $query = TicketUpdate::where('ticket_id', $ticket->id)
            ->withUser()
            ->orderBy('created_at', 'asc');

        // Regular users can only see public updates
        // Agents and admins can see all updates including internal notes
        if (!($user->isRole('admin') || $user->isRole('agent'))) {
            $query->public();
        }

        return $query->get();
    }

    /**
     * Create a new update/comment on a ticket
     */
    public function createUpdate(Ticket $ticket, array $payload, User $actor): TicketUpdate
    {
        // Ensure user_id and ticket_id are set
        $payload['user_id'] = $actor->id;
        $payload['ticket_id'] = $ticket->id;

        // Default type to 'comment' if not specified
        $payload['type'] = $payload['type'] ?? TicketUpdate::TYPE_COMMENT;

        // Only agents/admins can create internal notes
        if (isset($payload['is_internal']) && $payload['is_internal']) {
            if (!($actor->isRole('admin') || $actor->isRole('agent'))) {
                abort(403, 'Only agents and admins can create internal notes.');
            }
        }

        $update = TicketUpdate::create($payload);

        return $update->load('user:id,first_name,last_name,email');
    }


}