<?php

namespace App\Controller\Admin;

use App\Service\FileUploader;
use App\Entity\Course;
use App\Entity\Module;
use App\Entity\Purchase;
use App\Form\CourseType;
use App\Form\ModuleType;
use App\Repository\CourseRepository;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/admin/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET"})
     */
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('admin/course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/available", name="course_available_public_index", methods={"GET"})
     */
    public function availablePublicIndex(CourseRepository $courseRepository, PurchaseRepository $purchaseRepository): Response
    {
        return $this->render('admin/course/publicIndex.html.twig', [
            'courses' => $courseRepository->findBy(['disabled'=>false]),
            'purchases' => $purchaseRepository->findByUser($this->getUser())
        ]);
    }
    
    /**
     * @Route("/owner", name="course_owner_public_index", methods={"GET"})
     */
    public function ownerPublicIndex(PurchaseRepository $purchaseRepository): Response
    {
        return $this->render('admin/course/ownerIndex.html.twig', [
            'purchases' => $purchaseRepository->findByUser($this->getUser())
        ]);
    }

    /**
     * @Route("/new", name="course_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $course = new Course();
        $course->setDisabled(true);
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('headerImage')->getData();
            if ($logoFile) {                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('courses_directory'));
                $course->setHeaderImage($logoFileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('notice', "<strong class='text-success'>Curso creado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('course_index');
        }

        return $this->render('admin/course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/activate", name="course_activate", methods={"GET","POST"})
     */
    public function activated(Request $request, Course $course): Response
    {
        if(count($course->getModules())){       
            $course->setDisabled(false);
            $this->getDoctrine()->getManager()->flush();
        
            $this->addFlash('notice', "<strong class='text-success'>Curso activado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
        }else{
            // Can not activate !!
            $this->addFlash('notice', "<strong class='text-danger'>ERROR</strong><br><span class='text-dark-tp4 text-110'>El curso no se pudo activar porque no tiene contenido</span>");
        }
        return $this->redirectToRoute('course_show', array('id'=>$course->getId()));
    }
    
    /**
     * @Route("/{id}/deactivate", name="course_deactivate", methods={"GET","POST"})
     */
    public function deactivated(Request $request, Course $course): Response
    {
        $course->setDisabled(true);
        $this->getDoctrine()->getManager()->flush();
        
        $this->addFlash('notice', "<strong class='text-success'>Curso desactivado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
        return $this->redirectToRoute('course_show', array('id'=>$course->getId()));
    }

    /**
     * @Route("/public/{id}", name="course_public_show", methods={"GET"})
     */
    public function publicShow(Purchase $purchase): Response
    {
        return $this->render('admin/course/publicShow.html.twig', [
            'course' => $purchase->getCourse(), 'purchase' => $purchase
        ]);
    }            
    
    /**
     * @Route("/{id}/{tab}", name="course_show", requirements={"tab": "modules"}, methods={"GET"})
     */
    public function show(Course $course, $tab = 'index'): Response
    {
        return $this->render('admin/course/show.html.twig', [
            'course' => $course, 'tab' => $tab
        ]);
    }

    /**
     * @Route("/{id}/edit", name="course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Course $course, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('headerImage')->getData();
            if ($logoFile) {
                // Remove old image
                if($course->getHeaderImage()){
                    $filesystem->remove($this->getParameter('courses_directory').'/'.$course->getHeaderImage());
                }
                                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('courses_directory'));
                $course->setHeaderImage($logoFileName);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Curso actualizado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('course_show', array('id'=>$course->getId()));
        }

        return $this->render('admin/course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($course);
            $entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }
    
    /**
     * @Route("/{id}/deleteHeaderImage", name="course_deleteHeaderImage", methods={"POST"})
     */
    public function deleteHeaderImage(Request $request, Course $course, Filesystem $filesystem): JsonResponse
    {
        if($course->getHeaderImage()){
            $filesystem->remove($this->getParameter('courses_directory').'/'.$course->getHeaderImage());
            $course->setHeaderImage(null);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }        
    
    
    
    /**
     * @Route("/public/module/{id}/module/{moduleId}", name="course_module_show", methods={"GET"})
     */
    public function publicModuleShow(Purchase $purchase, Module $module): Response
    {
        return $this->render('admin/course/publicModuleShow.html.twig', [
            'purchase' => $purchase, 
            'module' => $module
        ]);
    }
    
    /**
     * @Route("/{id}/module/new", name="course_module_new", methods={"GET","POST"})
     */
    public function newModule(Request $request, Course $course, FileUploader $fileUploader): Response
    {
        $module = new Module();
        $module->setCourse($course);
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);
                        
        if ($form->isSubmitted() && $form->isValid()) {                                
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('headerImage')->getData();
            if ($logoFile) {                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('module_images_directory'));
                $module->setHeaderImage($logoFileName);
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();

            $this->addFlash('notice', "<strong class='text-success'>Módulo creado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('course_show', array('id'=>$course->getId(), 'tab'=>'modules'));
        }

        return $this->render('admin/course/module/new.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/module/{moduleId}", name="course_module_edit", methods={"GET","POST"})
     */
    public function editModule(Request $request, Course $course, Module $module, FileUploader $fileUploader, Filesystem $filesystem): Response
    {        
        $form = $this->createForm(ModuleType::class, $module);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {           
            /** @var UploadedFile $logoFile */
            $logoFile = $form->get('headerImage')->getData();
            if ($logoFile) {
                // Remove old image
                if($module->getHeaderImage()){
                    $filesystem->remove($this->getParameter('module_images_directory').'/'.$module->getHeaderImage());
                }
                                
                $logoFileName = $fileUploader->upload($logoFile, $this->getParameter('module_images_directory'));
                $module->setHeaderImage($logoFileName);
            }
                        
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Módulo actualizado</strong><br><span class='text-dark-tp4 text-110'>¡Tus cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('course_show', array('id'=>$course->getId(), 'tab'=>'modules'));
        }

        return $this->render('admin/course/module/edit.html.twig', [
            'module' => $module,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}/deleteModuleHeaderImage", name="course_module_deleteHeaderImage", methods={"POST"})
     */
    public function deleteModuleHeaderImage(Request $request, Module $module, Filesystem $filesystem): JsonResponse
    {
        if($module->getHeaderImage()){
            $filesystem->remove($this->getParameter('module_images_directory').'/'.$module->getHeaderImage());
            $module->setHeaderImage(null);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/uploadFile", name="course_module_uploadFile", methods={"POST"})
     */
    public function uploadFile(Request $request, FileUploader $fileUploader): JsonResponse
    {
        $file = $request->files->get('textFile');        
        $webPathFilename = '/uploads/temp/' . $fileUploader->upload($file, $this->getParameter('temp_directory'));
        
        return new JsonResponse($webPathFilename);
    }
    
    /**
     * @Route("/contractCourse", name="course_contract_course", methods={"POST"})
     */
    public function contractCourse(Request $request, CourseRepository $courseRepository, PurchaseRepository $purchaseRepository){
        $courseId = $request->request->get('courseId');
        $course = $courseRepository->findOneById($courseId);
        $user = $this->getUser();
        
        $oldPurchase = $purchaseRepository->findBy([ 'user'=>$user, 'course'=>$course ]);
        if(!count($oldPurchase)){
            $newPurchase = new Purchase();
            $newPurchase->setUser($user);
            $newPurchase->setCourse($course);
            $newPurchase->setPrice((($course->getPrice())?$course->getPrice():0));
            $newPurchase->setCreationDate(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newPurchase);
            $entityManager->flush();
        }
        
        return new JsonResponse(true);
    }
}
