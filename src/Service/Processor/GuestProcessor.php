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
    private $phone;

    public function __construct(GuestRepository $repo, EntityManagerInterface $em)
    {
        $this->repo = $repo;
        $this->em = $em;
    }

    public function setGuestName($guestName)
    {
        $this->guestName = $guestName;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getResponse()
    {
        $firstAndLast = explode(" ", $this->guestName, 2);

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
        } else {
            $potentialMatches = $this->repo->findBy(['phone' => $this->phone]);

            if (!$potentialMatches) {
                $potentialMatches = $this->repo->findBy(['lastName' => $firstAndLast[1]]);
            }

            if (!$potentialMatches) {
                $potentialMatches = $this->repo->findBy(['firstName' => $firstAndLast[0]]);
            }

            return $this->getPotentialMessage($name, $potentialMatches);
        }

        return $this->getErrorMessage($name);
    }

    private function getNameList($guests)
    {
        $guests = array_map(function($guest) {
            return $guest->getFirstAndLast();
        }, $guests);

        return implode(', ', $guests);
    }

    private function getPotentialMessage($name, $potentialMatches)
    {
        $nameList = $this->getNameList($potentialMatches);
        $message = <<<EOT
I could not find a record for the name:
${name['firstName']} ${name['lastName']}. 
Does one of the following names look like it could be a match?
$nameList
EOT;

        return $message;
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
You may try again with a different variation of your name, or contact Clark directly at 352-613-1150.
EOT;

        return $message;
    }
}
