<?php

namespace App\Service\Processor;

use App\Service\Contract\Processor;
use App\Repository\GuestRepository;
use Doctrine\ORM\EntityManagerInterface;

class GuestProcessor implements Processor
{
    private $repo;
    private $em;
    private $guestName;

    public function __construct(GuestRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    public function setGuestName($guestName)
    {
        $this->guestName = $guestName;
    }

    public function getResponse()
    {
        $firstAndLast = explode(" ", $this->guestName);

        if (count($firstAndLast) !== 2) {
            return null;
        }

        $name = [
            'firstName' => $firstAndLast[0],
            'lastName' => $firstAndLast[1]
        ];

        $guest = $this->repo->findOneBy($name);

        if ($guest !== null) {
            $guest->setAttending(true);
            $this->em->persist($guest);
            $this->em->flush();

            return $this->getRecognitionMessage();
        }

        return $this->getErrorMessage($name);
    }

    private function getRecognitionMessage()
    {
        return 'Thank you for RSVPing! We look forward to seeing you!';
    }

    private function getErrorMessage($name)
    {
        $message = <<<EOT
            I could not find a record for the name:
            ${name['firstName']} ${name['lastName']}. 
            You may try again with a different variation of your name, 
            or contact Clark directly at 352-613-1150.
EOT;

        return $message;
    }
}
