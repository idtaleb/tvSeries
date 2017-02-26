<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Episode;
use AppBundle\Entity\TvSeries;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Episode controller.
 *
 * @Route("episode")
 */
class EpisodeController extends Controller
{
    /**
     * Lists all episode entities.
     *
     * @Route("allEpisode/{id}", name="episode_index")
     * @Method("GET")
     */
    public function indexAction(Request $request, TvSeries $tvSeries)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Episode')->createQueryBuilder('e')
                        ->select('e')
                        ->andWhere('e.tvSerie IN (:tvSerie)')
                        ->setParameter('tvSerie', $tvSeries);

        $query = $queryBuilder->getQuery();

        $paginator  = $this->get('knp_paginator');

        $episodes = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page',1) /*page number*/,
            $request->query->getInt('limit',5) /*limit per page*/
        );
        return $this->render('episode/index.html.twig', array(
            'episodes' => $episodes,
        ));
    }

    /**
     * Creates a new episode entity.
     *
     * @Route("/new/{id}", name="episode_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request , TvSeries $tvSeries)
    {
        //dump($tvSeries);die();
        //$tvSeries = $this->getDoctrine()->getManager()->getRepository('AppBundle:TvSeries')->find($tvSeries);

        $episode = new Episode();
        $form = $this->createFormBuilder($episode)
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('episodeNumber', IntegerType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('datePublish', DateTimeType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('image', FileType::class, array('required' => false), array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form['name']->getData();
            $episodeNumber = $form['episodeNumber']->getData();
            $datePublish = $form['datePublish']->getData();
            $description = $form['description']->getData();
            $file = $episode->getImage();
            if($file != null){
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('image_directory'),
                    $fileName);
                $episode->setImage($fileName);
            }
            else{
                $episode->setImage("");
            }

            $episode->setName($name);
            $episode->setEpisodeNumber($episodeNumber);
            $episode->setDatePublish($datePublish);
            $episode->setDescription($description);
            $episode->setTvSerie($tvSeries);

            $em = $this->getDoctrine()->getManager();
            $em->persist($episode);
            $em->flush($episode);

            return $this->redirectToRoute('episode_show', array('id' => $episode->getId()));
        }

        return $this->render('episode/new.html.twig', array(
            'episode' => $episode,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a episode entity.
     *
     * @Route("/{id}", name="episode_show")
     * @Method("GET")
     */
    public function showAction(Episode $episode)
    {
        $deleteForm = $this->createDeleteForm($episode);

        return $this->render('episode/show.html.twig', array(
            'episode' => $episode,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing episode entity.
     *
     * @Route("/{id}/edit", name="episode_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Episode $episode)
    {
        $deleteForm = $this->createDeleteForm($episode);
        $editForm = $this->createForm('AppBundle\Form\EpisodeType', $episode);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('episode_edit', array('id' => $episode->getId()));
        }

        return $this->render('episode/edit.html.twig', array(
            'episode' => $episode,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a episode entity.
     *
     * @Route("/{id}", name="episode_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Episode $episode)
    {
        $form = $this->createDeleteForm($episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($episode);
            $em->flush($episode);
        }

        return $this->redirectToRoute('episode_index');
    }

    /**
     * Creates a form to delete a episode entity.
     *
     * @param Episode $episode The episode entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Episode $episode)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('episode_delete', array('id' => $episode->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
