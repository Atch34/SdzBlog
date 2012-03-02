<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        // On ne sait pas combien de pages il y a, mais on sait qu'une page
        // doit être supérieure ou égale à 1.
        if( $page < 1 )
        {
            // On déclenche une exception NotFoundHttpException, cela va afficher
            // la page d'erreur 404 (on pourra personnaliser cette page plus tard, d'ailleurs).
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

        // Pour récupérer la liste de tous les articles : on utilise findAll()
        $articles = $this->getDoctrine()
                         ->getEntityManager()
                         ->getRepository('SdzBlogBundle:Article')
                         ->findAll();

        // L'appel de la vue ne change pas
        return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles
        ));
    }


    public function voirAction($id)
    {
        // Pour récupérer un article unique : on utilise find()
        $article = $this->getDoctrine()
                        ->getEntityManager()
                        ->getRepository('SdzBlogBundle:Article')
                        ->find($id);

        // Si l'article n'existe pas, on affiche une erreur 404
        if( $article == null )
        {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
            'article' => $article
        ));
    }

    public function ajouterAction()
    {
        // La gestion d'un formulaire est particulière, mais l'idée est la suivante :

        if( $this->get('request')->getMethod() == 'POST' )
        {
            // Ici, on s'occupera de la création et de la gestion du formulaire.

            $this->get('session')->setFlash('info', 'Article bien enregistré');

            // Puis on redirige vers la page de visualisation de cet article.
            return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => 5)) );
        }

        // Si on n'est pas en POST, alors on affiche le formulaire.
        return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
    }

    public function modifierAction($id)
    {
        $article = $this->getDoctrine()
                        ->getEntityManager()
                        ->getRepository('SdzBlogBundle:Article')
                        ->find($id);

        // Si l'article n'existe pas, on affiche une erreur 404
        if( $article == null )
        {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        // Ici, on s'occupera de la création et de la gestion du formulaire.

        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
            'article' => $article
        ));
    }

    public function supprimerAction($id)
    {
        $article = $this->getDoctrine()
                        ->getEntityManager()
                        ->getRepository('SdzBlogBundle:Article')
                        ->find($id);

        // Si l'article n'existe pas, on affiche une erreur 404
        if( $article == null )
        {
            throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
        }

        if( $this->get('request')->getMethod() == 'POST' )
        {
            // Si la requête est en POST, on supprimera l'article.
            $this->get('session')->setFlash('info', 'Article bien supprimé');

            // Puis on redirige vers l'accueil.
            return $this->redirect( $this->generateUrl('sdzblog_accueil') );
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer.
        return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array(
            'article' => $article
        ));
    }
}