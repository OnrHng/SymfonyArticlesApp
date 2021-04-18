<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
      * @Route("/", name="articleList")
      */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('/articles/index.html.twig', array(
            'articles' => $articles
        ));
    }

    /**
     * @Route ("/article/saveNewArticle", name="newArticle")
     * Method({"GET", "POST"})
     */
    public function saveNewArticle(Request $request)
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class,
                array('attr' => array(
                        'class' => 'form-control'
                ))
            )
            ->add('ArtBody', TextareaType::class,
                array('attr' => array(
                        'class' => 'form-control'
                ))
            )
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('articleList');
        }

        return $this->render('articles/saveNewArticle.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route ("/article/editArticle/{id}", name="editArticle")
     * Method({"GET", "POST"})
     */
    public function editArticle(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class,
                array('attr' => array(
                    'class' => 'form-control'
                ))
            )
            ->add('ArtBody', TextareaType::class,
                array('attr' => array(
                    'class' => 'form-control'
                ))
            )
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('articleList');
        }

        return $this->render('articles/editArticle.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route ("/article/{id}", name="displayArticle")
     */
    public function displayArticle($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('articles/displayArticle.html.twig', array(
            'article' => $article
        ));
    }

    /**
     * @Route ("/article/delete/{id}")
     * Method({"DELETE"})
     */
    public function deleteArticle(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
