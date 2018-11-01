<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Guest;
use App\Service\Pipeline;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    /**
     * @Route("/twilio", defaults={"_format"="xml"}, name="twilio")
     * @Method({"POST"})
     */
    public function index(Pipeline $pipeline, Client $client) {

        $to = $_REQUEST['To'];
        $from = $_REQUEST['From'];
        $body = $_REQUEST['Body'];

        $response = $pipeline->getResponse($body, $from);

        $client->messages->create(
            $from,
            [
                'from' => $to,
                'body' => $response
            ]
        );
        
        return new Response(
            null,
            Response::HTTP_OK,
            ['content-type' => 'text/plain']
        );
    }
}
