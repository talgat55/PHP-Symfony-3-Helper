<?php

namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use AppBundle\Form\PostType;
class PostController extends Controller
{

    /**
     * @param Request $request
     * @Route("/blogs/create", name="post.create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

        $form = $this->createForm(PostType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('image')->getData();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $blogPost = $form->getData();

            $file->move(
                $this->getParameter('post_directory'),
                $fileName
            );

            $em = $this->getDoctrine()->getManager();
            $blogPost->setImage($fileName);
            $em->persist($blogPost);
            $em->flush();

            return $this->redirectToRoute('post.index');
        }
        return $this->render('AppBundle:Post:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blogs", name="post.index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Post')->findAll();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), /*page number*/
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
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }




}
