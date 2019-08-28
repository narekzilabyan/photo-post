<?php
/**
 * Created by PhpStorm.
 * User: n
 * Date: 8/28/19
 * Time: 12:57 PM
 */

namespace App\Controller\API;


use App\Entity\Vote;
use App\Form\VoteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/vote", name="api_vote", methods={"POST"})
     */
    public function vote(Request $request)
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $post = $request->query->get('post');
//        $vote = $request->query->get('vote');
//
//
//        $vote = $em->getRepository(Vote::class)->findOneBy(
//            ['post' => $post, 'user' => $this->getUser()]
//        );
//
//        if ($vote) {
            return new JsonResponse('', Response::HTTP_CREATED);
//        }
//
//
//
//        $vote = new Vote();
//
//        $vote->setUser($this->getUser());
//
//        $em->persist($vote);
//        $em->flush();
//
//            // update total votes
//            $em->getRepository(Vote::class)->updateRating($vote->getPost()->getId());
//
//            return new JsonResponse((string)$form->getErrors(true), Response::HTTP_CREATED);
//        }
//
//        return new JsonResponse((string)$form->getErrors(true), Response::HTTP_BAD_REQUEST);

    }
}