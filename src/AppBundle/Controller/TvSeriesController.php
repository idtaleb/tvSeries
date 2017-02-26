<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TvSeries;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tvseries controller.
 *
 * @Route("tvseries")
 */
class TvSeriesController extends Controller
{
    /**
     * Lists all tvSeries entities.
     *
     * @Route("/", name="tvseries_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:TvSeries')->createQueryBuilder('ts');
        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');

        $tvSeries = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page',1) /*page number*/,
            $request->query->getInt('limit',5) /*limit per page*/
        );
        return $this->render('tvseries/index.html.twig', array(
            'tvSeries' => $tvSeries,
        ));
    }

    /**
     * Creates a new tvSeries entity.
     *
     * @Route("/new", name="tvseries_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $tvSeries = new Tvseries();
        /*$form = $this->createForm('AppBundle\Form\TvSeriesType', $tvSeries);*/
        $form = $this->createFormBuilder($tvSeries)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('author', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('released_at', DateTimeType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('image', FileType::class, array('required' => false), array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form['name']->getData();
            $author = $form['author']->getData();
            $released_at = $form['released_at']->getData();
            $description = $form['description']->getData();
            $file = $tvSeries->getImage();
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName);
                $tvSeries->setImage($fileName);
            } else {
                $tvSeries->setImage("");
            }

            $tvSeries->setName($name);
            $tvSeries->setAuthor($author);
            $tvSeries->setReleasedAt($released_at);
            $tvSeries->setDescription($description);
            //$tvSeries->setImage($image);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tvSeries);
            $em->flush($tvSeries);

            return $this->redirectToRoute('tvseries_show', array('id' => $tvSeries->getId()));
        }

        return $this->render('tvseries/new.html.twig', array(
            'tvSeries' => $tvSeries,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a tvSeries entity.
     *
     * @Route("/{id}", name="tvseries_show")
     * @Method("GET")
     */
    public function showAction(TvSeries $tvSeries)
    {
        $deleteForm = $this->createDeleteForm($tvSeries);

        return $this->render('tvseries/show.html.twig', array(
            'tvSeries' => $tvSeries,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing tvSeries entity.
     *
     * @Route("/{id}/edit", name="tvseries_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TvSeries $tvSeries)
    {
        $deleteForm = $this->createDeleteForm($tvSeries);
        $editForm = $this->createForm('AppBundle\Form\TvSeriesType', $tvSeries);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tvseries_edit', array('id' => $tvSeries->getId()));
        }

        return $this->render('tvseries/edit.html.twig', array(
            'tvSeries' => $tvSeries,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a tvSeries entity.
     *
     * @Route("/{id}", name="tvseries_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TvSeries $tvSeries)
    {
        $form = $this->createDeleteForm($tvSeries);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tvSeries);
            $em->flush($tvSeries);
        }

        return $this->redirectToRoute('tvseries_index');
    }

    /**
     * Creates a form to delete a tvSeries entity.
     *
     * @param TvSeries $tvSeries The tvSeries entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TvSeries $tvSeries)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tvseries_delete', array('id' => $tvSeries->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
