<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Request\File;
use AppBundle\Entity\Post;

use AppBundle\Form\PostType;
use AppBundle\Form\PostTypeLight;

class CrudController extends Controller
{
    /**
     * @Route("/post/{post_id}", name="postDetails"  )
     */
    public function postAction($post_id)
    {

        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->findBy(array('id' => $post_id))[0];

        $user = $this->getUser();

        if ($user)
            $username = $user->getUsername();
        else
            $username = null;

        if(!$post)
            return $this->render('default/post.html.twig', array(
                'post' => 'not found',
                '$user' => $username
            ));
        else
            return $this->render('default/post.html.twig', array(
                'post' => $post,
                'user' => $username
            ));
    }

    /**
     * @Route("/post/edit/{post_id}", name="editPostAction" )
     */
    public function editAction($post_id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($post_id);

        $editForm = $this->createForm(PostTypeLight::class, $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid())
        {
            $post = $editForm->getData();
            $entityManager->flush();

            $this->addFlash(
                'success',
                'post.modified'
            );

            return $this->redirectToRoute('homepage', array('page' => 1));
        }

        $user = $this->getUser();

        if ($user)
            if($post->getAuthor() != $user->getUserName())
            {
                $this->addFlash(
                    'error',
                    'error.unauthorized.modification'
                );

                return $this->redirectToRoute('homepage', array('page' => 1));
            }

        return $this->render('default/editPost.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/post/delete/{post_id}", name="deletePostAction" )
     */
    public function deleteAction($post_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($post_id);

        if(!$post) {

            $this->addFlash(
                'error',
                'error.delete'
            );

            return $this->redirectToRoute('homepage', array('page' => 1));
        }

        $user = $this->getUser();

        if ($user)
            if($post->getAuthor() != $user->getUserName())
            {
                $this->addFlash(
                    'error',
                    'error.unauthorized.suppression'
                );

                return $this->redirectToRoute('homepage', array('page' => 1));
            }

        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'post.deleted'
        );

        return $this->redirectToRoute('homepage', array('page' => 1));
    }
}
