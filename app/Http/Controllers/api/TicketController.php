<?php

namespace App\Http\Controllers\api;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Services\TicketService;
use Illuminate\Routing\Controller;
use App\Http\Requests\Ticket\CreateTicketRequest;
use App\Http\Requests\Ticket\CreateTicketUpdateRequest;

class TicketController extends Controller
{
    public function store(CreateTicketRequest $request, TicketService $service)
    {
        // Attach the current user as requester (if authenticated)
        $payload = $request->validated();
        $payload['user_id'] = $request->user()->id;

        $ticket = $service->create($payload);

        return response()->json($ticket, 201);
    }

    public function index(Request $request, TicketService $service)
    {
        $tickets = $service->getVisibleTicketsFor($request->user());
        return response()->json($tickets);
    }

    public function show($id, TicketService $service)
    {
        $ticket = $service->getById($id);
        return response()->json($ticket);
    }

     /** Claim-only: succeeds only if currently unassigned (atomic). */
    public function assign($id, TicketService $service)
    {
        $ticket  = $service->getById($id);
        $updated = $service->assign($ticket, auth()->user());

        return response()->json($updated);
    }

    // /** Admin-only reassignment (overwrite). */
    // public function reassign($id, ReassignTicketRequest $request, TicketService $service)
    // {
    //     $ticket  = $service->getById($id);
    //     $assigneeId = (int)$request->validated()['assignee_id'];

    //     $updated = $service->reassign($ticket, $assigneeId, $request->user());
    //     return response()->json($updated);
    // }

    /** Unassign; optionally guard with expected_assignee_id to avoid races. */
    public function unassign($id, TicketService $service)
    {
        $ticket = $service->getById($id);
        $updated = $service->unassign($ticket, auth()->user());

        return response()->json($updated);
    }

    public function resolve($id, Request $request, TicketService $service)
    {
        $ticket = Ticket::findOrFail($id);

        $resolved = $service->resolve($ticket, $request->user());

        return response()->json($resolved);
    }

    /* ========= Ticket Updates ========= */

    /**
     * Get all updates for a specific ticket
     */
    public function getUpdates($id, Request $request, TicketService $service)
    {
        $ticket = $service->getById($id);
        $updates = $service->getUpdatesForTicket($ticket, $request->user());

        return response()->json($updates);
    }

    /**
     * Create a new update/comment on a ticket
     */
    public function createUpdate($id, CreateTicketUpdateRequest $request, TicketService $service)
    {
        $ticket = $service->getById($id);
        $payload = $request->validated();

        $update = $service->createUpdate($ticket, $payload, $request->user());

        return response()->json($update, 201);
    }
}
