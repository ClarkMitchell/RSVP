<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Guest;

class GuestController extends Controller
{
    /**
     * @Route("/guest/{id}", name="guest")
     */
    public function index($id, SerializerInterface $serializer)
    {
        $guest = $this->getDoctrine()
            ->getRepository(Guest::class)
            ->find($id);

        return new Response(
            $serializer->serialize($guest, 'json')
        );
    }
}
