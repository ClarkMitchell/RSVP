<?php

namespace App\Service\Processor;

use App\Service\Contract\Processor;
use App\Repository\GuestRepository;

class NoRsvpProcessor implements Processor
{
    private $repo;

    public function __construct(GuestRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getResponse()
    {
        return $this->repo->getGuestList(false) ?? 'All guests have RSVPed';
    }
}
