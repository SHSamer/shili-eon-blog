<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;

use AppBundle\Form\PostType;
use AppBundle\Form\PostSearchType;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;

class BlogController extends Controller
{
    /**
     * @Route("")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('homepage', array('page' => 1));
    }

    /**
     * @Route("/{page}", name="homepage", requirements = {"page" = "\d+"}, defaults={1} )
     */
    public function homeAction($page, Request $request)
    {
        $posts_repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $posts = $posts_repository->getPosts($page, 4);

        $post = new Post();
        $post->setTitle('');

        $searchForm = $this->createForm(PostSearchType::class, $post);

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid())
        {
            $posts_repository = $this->getDoctrine()->getRepository('AppBundle:Post');

            $posts = $posts_repository->getPostsLike($post->getTitle(), $page, 4);
        }

        $createForm = $this->createForm(PostType::class, $post);

        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid())
        {
            $user = $this->getUser();

            if (!$user)
            {
                $this->addFlash(
                    'error',
                    'error.unauthorized.creation'
                );

                return $this->redirectToRoute('homepage', array('page' => 1));
            }

            $validTitle = $this->getDoctrine()->getRepository('AppBundle:Post')->findBy(array('title' => $post->getTitle()));

            if($validTitle != null)
            {
                $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'error.title');

                return $this->redirectToRoute('homepage', array('page' => 1));
            }

            $post->setPublished( new \DateTime());

            $post->setAuthor($user->getUserName());

            $post->setAliasUrl($post->getTitle());

            if ($post->getImageUrl() != null)
            {
                $fileName = md5(uniqid()) . '.' . $post->getImageUrl()->guessExtension();

                try
                {
                    $post->getImageUrl()->move(
                        $this->container->getParameter('kernel.root_dir').'/../web/image',
                        $fileName
                    );
                }
                catch (FileException $e)
                {
                }
            }
            else
                $fileName = null;

            $post->setImageUrl($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'post.created');
        }

        $user = $this->getUser();

        $username = null;

        if($user != null)
            $username = $user->getUsername();

        return $this->render('default/home.html.twig',
            array(
                'posts' => $posts,
                'page' => $page,
                'create_form' => $createForm->createView(),
                'user' => $username,
                'search_form' => $searchForm->createView()
            )
        );
    }

    /**
     * @Route("/about", name="aboutpage" )
     */
    public function aboutAction()
    {
        $user= $this->getUser();

        $username = null;

        if($user)
            $username = $user->getUsername();

        return $this->render('default/about.html.twig', array(
            'user' => $username
        ));
    }
}
