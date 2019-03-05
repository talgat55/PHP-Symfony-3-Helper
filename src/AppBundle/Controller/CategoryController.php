<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\CategoryType;

class CategoryController extends Controller
{


    /**
     * @Route("/category/", name="category.index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:Category')->findAllCateriesforApi();
        function search($data,  $current, $parent = null)
        {
            foreach ($data as $item) {


                if ($item->getParentId() == $parent) {

                    if($item->getName()){
                        $value =  $item->getName();
                    }else{
                        $value = 0;
                    }

                    array_push($current, [$value]);

                    search($data, $current, $item->getId());
                }
            }
            return $current;
        }



        dump(search($query,  [], $parent = null));
       // dump($query);

        return $this->render('AppBundle:Category:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @param Request $request
     * @Route("/category/create", name="category.create")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {

//        $request = $request->get('test');

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $blogPost = $form->getData();
            $em = $this->getDoctrine()->getManager();
            dump($blogPost);

            $em->persist($blogPost);
            $em->flush();

            return $this->redirectToRoute('category.index');
        }
        return $this->render('AppBundle:Category:create.html.twig', array(
            'form' => $form->createView(),
        ));
//        $response = new Response();
//        $response->setContent(json_encode(array(
//            $request
//        )));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
    }

    /**
     * @Route("/edit")
     */
    public function editAction()
    {
        return $this->render('AppBundle:Category:edit.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/delete")
     */
    public function deleteAction()
    {
        return $this->render('AppBundle:Category:delete.html.twig', array(
            // ...
        ));
    }

      /**
     * @Route("/category/get")
     */
    public function getAction()
    {
        $categories = $this
            ->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $response = new Response();
        $response->setContent(json_encode(array(
            $request
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



}
