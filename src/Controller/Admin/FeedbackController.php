<?php

namespace App\Controller\Admin;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/admin/website/feedback")
 */
class FeedbackController extends AbstractController
{    
    /**
     * @Route("/new", name="feedback_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $feedback = new Feedback();        
        
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {    
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('feedbacks_directory'));
                $feedback->setLogo($logoFileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($feedback);
            $entityManager->flush();
            
            $this->addFlash('notice', "<strong class='text-success'>Opinión creada</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('website_index', ['tab'=>'feedback']);
        }

        return $this->render('admin/website/feedback/new.html.twig', [
            'feedback' => $feedback,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="feedback_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Feedback $feedback, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('logo')->getData();
            if ($logoFile) {
                // Remove old photo
                if($feedback->getLogo()){
                    $filesystem->remove($this->getParameter('feedbacks_directory').'/'.$feedback->getLogo());
                }
                                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('feedbacks_directory'));
                $feedback->setLogo($logoFileName);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Opinión actualizada</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('website_index', ['tab'=>'feedback']);
        }

        return $this->render('admin/website/feedback/edit.html.twig', [
            'feedback' => $feedback,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="feedback_delete", methods={"POST"})
     */
    public function delete(Request $request, Feedback $feedback, Filesystem $filesystem): JsonResponse
    {
        if($feedback->getLogo()){
            $filesystem->remove($this->getParameter('feedbacks_directory').'/'.$feedback->getLogo());
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($feedback);
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/{id}/deleteLogo", name="feedback_deleteLogo", methods={"POST"})
     */
    public function deleteLogo(Request $request, Feedback $feedback, Filesystem $filesystem): JsonResponse
    {
        if($feedback->getLogo()){
            $filesystem->remove($this->getParameter('feedbacks_directory').'/'.$feedback->getLogo());
            $feedback->setLogo(null);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }
}