<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/admin/website/activity")
 */
class ActivityController extends AbstractController
{    
    /**
     * @Route("/new", name="activity_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $activity = new Activity();        
        
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {    
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('image')->getData();
            if ($logoFile) {                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('activities_directory'));
                $activity->setImage($logoFileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activity);
            $entityManager->flush();
            
            $this->addFlash('notice', "<strong class='text-success'>Actividad creada</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('website_index', ['tab'=>'activity']);
        }

        return $this->render('admin/website/activity/new.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activity_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activity $activity, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('image')->getData();
            if ($logoFile) {
                // Remove old photo
                if($activity->getImage()){
                    $filesystem->remove($this->getParameter('activities_directory').'/'.$activity->getImage());
                }
                                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('activities_directory'));
                $activity->setImage($logoFileName);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Actividad actualizada</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('website_index', ['tab'=>'activity']);
        }

        return $this->render('admin/website/activity/edit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="activity_delete", methods={"POST"})
     */
    public function delete(Request $request, Activity $activity, Filesystem $filesystem): JsonResponse
    {
        if($activity->getImage()){
            $filesystem->remove($this->getParameter('activities_directory').'/'.$activity->getImage());
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($activity);
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/{id}/deleteImage", name="activity_deleteImage", methods={"POST"})
     */
    public function deleteImage(Request $request, Activity $activity, Filesystem $filesystem): JsonResponse
    {
        if($activity->getImage()){
            $filesystem->remove($this->getParameter('activities_directory').'/'.$activity->getImage());
            $activity->setLogo(null);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }
}