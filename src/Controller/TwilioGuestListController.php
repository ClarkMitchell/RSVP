<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twilio\Twiml;
use App\Entity\Guest;

class TwilioGuestListController extends Controller
{
    /**
     * @Route("/twilio/guestlist", defaults={"_format"="xml"}, name="guestlist")
     * @Method({"POST"})
     */
    public function index(SerializerInterface $serializer, Request $request)
    {
        $attending = ['attending' => true];

        $guests = $this->getDoctrine()
            ->getRepository(Guest::class)
            ->findBy($attending);

        $response = new Twiml();

        $response->message(
            $this->formatGuestList($guests)
        );

        return new Response(
            $response,
            Response::HTTP_OK,
            ['content-type' => 'text/xml']
        );
    }

    /**
     * @var array $guests
     *
     * @return string $textFormat
     */
    private function formatGuestList($guests)
    {
        $textFormat = '';

        foreach ($guests as $guest) {
            $textFormat .= $guest->getFirstName();
            $textFormat .= ' ';
            $textFormat .= $guest->getLastName();

            if ($guest !== end($guests)) {
                $textFormat .= ', ';
            }
        }

        return $textFormat;
    }
}
