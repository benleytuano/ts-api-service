<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\TicketService;
use Illuminate\Routing\Controller;
use App\Http\Requests\Ticket\CreateTicketRequest;

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

    // Add update, delete, etc. as needed
}
