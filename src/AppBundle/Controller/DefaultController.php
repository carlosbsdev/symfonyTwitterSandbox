<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Abraham\TwitterOAuth\TwitterOAuth;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render('default/index.html.twig',
                array(
                        'contenido' => "Usando la API pública de Twitter, para más info consulta \"About\""
                    ));
    }
    
    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/about.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        
        $form = $this->createFormBuilder()
            ->add('user', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $query = $form->getData();

            $connection = new TwitterOAuth(
                $this->getParameter('twitter_consumer_api_key'),
                $this->getParameter('twitter_consumer_api_key_secret'),
                $this->getParameter('twitter_access_token'),
                $this->getParameter('twitter_access_token_secret')
                );
        
        //Only the first 1,000 matching results are available.
        $content = $connection->get("users/search", ["q" => $query]);
        
        return $this->render('default/search.html.twig',
                array(
                        'contenido' => $content,
                        'form' => $form->createView()
                    ));
        }
        
        return $this->render('default/search.html.twig',
                array(
                    'contenido' => null,
                        'form' => $form->createView()
                    ));
    }
    /**
     * @Route("/trending", name="trends")
     */
    public function trendsAction(Request $request)
    {
        
            $connection = new TwitterOAuth(
                $this->getParameter('twitter_consumer_api_key'),
                $this->getParameter('twitter_consumer_api_key_secret'),
                $this->getParameter('twitter_access_token'),
                $this->getParameter('twitter_access_token_secret')
                );
        
        //Returns the top 50 trending topics for a specific WOEID, if trending information is available for it.
        $content = $connection->get("trends/place", ["id" => 1]);
        
        return $this->render('default/trends.html.twig',
                array(
                        'contenido' => $content
                    ));
        
    }
}
