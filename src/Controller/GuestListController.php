<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Guest;

class GuestListController extends Controller
{
    /**
     * @Route("/guestlist", name="guest")
     */
    public function index(SerializerInterface $serializer)
    {
        $attending = ['attending' => true];

        $guests = $this->getDoctrine()
            ->getRepository(Guest::class)
            ->findBy($attending);

        return new Response(
            $serializer->serialize($guests, 'json')
        );
    }
}