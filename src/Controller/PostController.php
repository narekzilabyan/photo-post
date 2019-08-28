<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Vote;
use App\Form\PostType;
use App\Form\VoteType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PostController extends AbstractController
{
    /**
     * @Route("/list", name="post_list")
     * @Template()
     */
    public function all()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository(Post::class)->findAll();

        return ['posts' => $posts];
    }

    /**
     * @Route("/show/{id}", name="post_show")
     * @Template()
     */
    public function show(Post $post)
    {
        $em = $this->getDoctrine()->getManager();

        $vote = $em->getRepository(Vote::class)->findOneBy(
            ['post' => $post, 'user' => $this->getUser()]
        );

        return ['post' => $post, 'vote' => $vote];
    }

    /**
     * @Route("/create", name="post_create")
     */
    public function create(Request $request, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setAuthor($this->getUser());

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $brochureFile */
            $image = $form['image']->getData();
            if ($image) {
                $imageName = $fileUploader->upload($image);
                $post->setImageName($imageName);
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="post_edit")
     */
    public function edit(Request $request, Post $post, FileUploader $fileUploader)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $brochureFile */
            $image = $form['image']->getData();
            if ($image) {
                $imageName = $fileUploader->upload($image);
                $post->setImageName($imageName);
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete", name="post_delete")
     */
    public function delete()
    {}

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/vote", name="post_vote")
     */
    public function vote(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $postId = $request->get('postId');
        $voteNumber = $request->get('vote');

        $post = $em->getRepository(Post::class)->find($postId);

        $vote = $em->getRepository(Vote::class)->findOneBy(
            ['post' => $post, 'user' => $this->getUser()]
        );

        if ($post && !$vote){
            $vote = new Vote();
            $vote->setPost($post);
            $vote->setUser($this->getUser());
            $vote->setVote(intval($voteNumber));

            $em->persist($vote);
            $em->flush();

            // update post reputation
            $em->getRepository(Post::class)->updateRating($vote->getPost()->getId());
        }

        return $this->redirectToRoute('post_show', array('id' => $post->getId()));
    }
}
