<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sdz\BlogBundle\Entity\Article;

class BlogController extends Controller
{
    public function indexAction($page)
    {
        // On récupère le repository
        $repository = $this->getDoctrine()
                           ->getEntityManager()
                           ->getRepository('SdzBlogBundle:Article');

        // On récupère le nombre total d'articles
        $nb_articles = $repository->getTotal();

        // On définit le nombre d'articles par page
        // (pour l'instant en dur dans le contrôleur, mais par la suite on le transformera en paramètre du bundle)
        $nb_articles_page = 2;

        // On calcul le nombre total de pages
        $nb_pages = ceil($nb_articles/$nb_articles_page);

        // On va récupérer les articles à partir du N-ième article :
        $offset = ($page-1) * $nb_articles_page;

        // Ici on a changé la condition pour déclencher une erreur 404
        // lorsque la page est inférieur à 1 ou supérieur au nombre max.
        if( $page < 1 OR $page > $nb_pages )
        {
            throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
        }

        // On récupère les articles qu'il faut grâce à findBy() :
        $articles = $repository->findBy(
            array(),                 // Pas de critère
            array('date' => 'desc'), // On tri par date décroissante
            $nb_articles_page,       // On sélectionne $nb_articles_page articles
            $offset                  // A partir du $offset ième
        );

        return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
            'articles' => $articles,
            'page'     => $page,    // On transmet à la vue la page courante,
            'nb_pages' => $nb_pages // Et le nombre total de pages.
        ));
    }


    public function voirAction(Article $article)
    {
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

    public function modifierAction(Article $article)
    {
        // Ici, on s'occupera de la création et de la gestion du formulaire.

        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
            'article' => $article
        ));
    }

    public function supprimerAction(Article $article)
    {
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