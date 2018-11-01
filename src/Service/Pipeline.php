<?php

namespace App\Service;

use App\Service\Processor\GuestListProcessor;
use App\Service\Processor\HeadCountProcessor;
use App\Service\Processor\GuestProcessor;
use App\Service\Processor\ErrorProcessor;

class Pipeline
{
    private $guestList;
    private $headCount;
    private $guest;
    private $error;

    public function __construct(
        GuestListProcessor $guestList,
        HeadCountProcessor $headCount,
        GuestProcessor $guest,
        ErrorProcessor $error
    ) {
        $this->guestList = $guestList;
        $this->headCount = $headCount;
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
