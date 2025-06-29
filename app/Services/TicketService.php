<?php

namespace App\Services;

use App\Models\Ticket;

class TicketService
{

    public function create($payload)
    {

        $ticket = Ticket::create($payload);

        return $ticket;

    }


}