<?php

namespace App\Services;


class TicketService
{

    public function create($payload)
    {

        $ticket = Ticket::create($payload);

        return $ticket;

    }


}