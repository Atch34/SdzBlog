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

        // Ici, on récupérera la liste des articles, puis on la passera au template.

        // Mais pour l'instant, on ne fait qu'appeler le template.
        return $this->render('SdzBlogBundle:Blog:index.html.twig');
    }


    public function voirAction($id)
    {
        // Ici, on récupérera l'article correspondant à l'id $id.

        return $this->render('SdzBlogBundle:Blog:voir.html.twig');
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
        // Ici, on récupérera l'article correspondant à l'$id.

        // Ici, on s'occupera de la création et de la gestion du formulaire.

        return $this->render('SdzBlogBundle:Blog:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
        // Ici, on récupérera l'article correspondant à l'$id.

        if( $this->get('request')->getMethod() == 'POST' )
        {
            // Si la requête est en POST, on supprimera l'article.
            $this->get('session')->setFlash('info', 'Article bien supprimé');

            // Puis on redirige vers l'accueil.
            return $this->redirect( $this->generateUrl('sdzblog_accueil') );
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer.
        return $this->render('SdzBlogBundle:Blog:supprimer.html.twig');
    }
}