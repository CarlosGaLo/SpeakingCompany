<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Form\StudentType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader): Response
    {
        $user = new User();
        // Random 20 char password
        $user->setPassword($passwordEncoder->encodePassword( $user, bin2hex(random_bytes(10)) ));
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {   
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                $photoFileName = $fileUploader->upload($photoFile, $this->getParameter('photos_directory'));
                $user->setPhoto($photoFileName);
            }
            
            // Always validate when create from admin panel
            $user->setStatus(1);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->forward('App\Controller\Admin\UserController::sendRestorePasswordMail', ['id'  => $user->getId() ]);
            
            $this->addFlash('notice', "<strong class='text-success'>Usuario creado</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                // Remove old photo
                if($user->getPhoto()){
                    $filesystem->remove($this->getParameter('photos_directory').'/'.$user->getPhoto());
                }
                
                $photoFileName = $fileUploader->upload($photoFile, $this->getParameter('photos_directory'));
                $user->setPhoto($photoFileName);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Usuario actualizado</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");            
            return $this->redirectToRoute('user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/student/{id}/edit", name="user_student_edit", methods={"GET","POST"})
     */
    public function editStudent(Request $request, User $user, FileUploader $fileUploader, Filesystem $filesystem): Response
    {
        $form = $this->createForm(StudentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $photoFile */
            $photoFile = $form->get('photo')->getData();
            if ($photoFile) {
                // Remove old photo
                if($user->getPhoto()){
                    $filesystem->remove($this->getParameter('photos_directory').'/'.$user->getPhoto());
                }
                
                $photoFileName = $fileUploader->upload($photoFile, $this->getParameter('photos_directory'));
                $user->setPhoto($photoFileName);
            }
            
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Estudiante actualizado</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");
            return $this->redirectToRoute('adminIndex');
        }

        return $this->render('admin/user/editStudent.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, Filesystem $filesystem): JsonResponse
    {
        if($user->getPhoto()){
            $filesystem->remove($this->getParameter('photos_directory').'/'.$user->getPhoto());
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/{id}/deletePhoto", name="user_deletePhoto", methods={"POST"})
     */
    public function deletePhoto(Request $request, User $user, Filesystem $filesystem): JsonResponse
    {
        if($user->getPhoto()){
            $filesystem->remove($this->getParameter('photos_directory').'/'.$user->getPhoto());
            $user->setPhoto(null);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/{id}/sendRestorePass", name="user_restorePass", methods={"GET","POST"})
     */
    public function sendRestorePasswordMail(Request $request, MailerInterface $mailer, User $user): JsonResponse
    {
        $response = ['error' => false, 'errorDescription' => null];                        
        
        $timestamp = time();
        $token = md5($user->getId() . $user->getEmail() . $timestamp . '-_-') . "!==!" . base64_encode($timestamp) . "!==!" . $user->getId();                
        
        // Send mails to company
        $company = $this->getDoctrine()->getManager()->getRepository(\App\Entity\Company::class)->getMain();
        
        $email = (new TemplatedEmail())
            ->from(new Address($company->getEmail(), $company->getName()))
            ->to(new Address($user->getEmail(), $user->getName()))            
            ->subject('[MQP] Solicitud de cambio de contraseña')           
            ->htmlTemplate('emails/admin/changePass.html.twig')
            ->context([
                'expirationDate' => new \DateTime('+1 day'),
                'token' => $token
            ]);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $response['error'] = true;
            $response['errorDescription'] = $e->getMessage();
        }        
        
        return new JsonResponse($response);
    }
}