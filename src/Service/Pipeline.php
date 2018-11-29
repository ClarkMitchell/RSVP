<?php

namespace App\Service;

use App\Service\Processor\GuestListProcessor;
use App\Service\Processor\HeadCountProcessor;
use App\Service\Processor\NoRsvpProcessor;
use App\Service\Processor\GuestProcessor;
use App\Service\Processor\ErrorProcessor;

class Pipeline
{
    private $guestList;
    private $headCount;
    private $noRsvp;
    private $guest;
    private $error;

    public function __construct(
        GuestListProcessor $guestList,
        HeadCountProcessor $headCount,
        NoRsvpProcessor $noRsvp,
        GuestProcessor $guest,
        ErrorProcessor $error
    ) {
        $this->guestList = $guestList;
        $this->headCount = $headCount;
        $this->noRsvp = $noRsvp;
        $this->guest = $guest;
        $this->error = $error;
    }

    public function getResponse($message, $phone)
    {
        $message = preg_replace(
            '/[^A-Za-z ]/',
            '',
            strtolower(trim($message)));

        if ($message === 'guestlist') {
            $response = $this->guestList->getResponse();
        } elseif ($message === 'headcount') {
            $response = $this->headCount->getResponse();
        } elseif ($message === 'no rsvp') {
            $response = $this->noRsvp->getResponse();
        } elseif ($message === 'commands') {
            $response = <<<EOT
guestlist, 
headcount, 
no rsvp
EOT;
        } else {
            $this->guest->setGuestName($message);
            $this->guest->setPhone($phone);
            $response = $this->guest->getResponse();
        }

        if ($response === null) {
            $response = $this->error->getResponse();
        }

        return $response;
    }
}
