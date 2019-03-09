<?php

namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class PostController extends Controller
{
    /**
     * @Route("/blogs/{page}", name="post.index")
     */
    public function indexAction($page= null)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page, /*page number*/
            10 /*limit per page*/
        );




        return $this->render('AppBundle:Post:index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/blogs/detail/{slug}", name="post.detail")
     */
    public function detailAction($slug)
    {
        if (!$slug) {
            throw $this->createNotFoundException('не найдена страница');
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findOneBy(['slug' => $slug]);

        return $this->render('AppBundle:Post:detail.html.twig', [
            'article' => $query
        ]);
    }


    /**
     * @param Request $request
     * @Route("/blogs/create", name="post.create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

        return $this->render('AppBundle:Post:create.html.twig', [
            'article' => $query
        ]);
    }




}
