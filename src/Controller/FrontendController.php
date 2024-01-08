<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use App\Entity\Contact;
use App\Form\ContactType;

use App\Repository\FeedbackRepository;
use App\Repository\ActivityRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FrontendController extends AbstractController {
    /**   
    * @Route("/wip", name="index") 
    */    
    public function index(){
        $number = random_int(0, 100);

        return $this->render('frontend/homeWIP.html.twig', [ 'number' => $number ]);
    }
    
    /**    
    * @Route("/")
    * @Route("/home", name="home")
    */    
    public function home(Request $request, UserRepository $userRepository, FeedbackRepository $feedbackRepository, ActivityRepository $activityRepository): Response
    {     
        $team = $userRepository->findAll();
        $feedbacks = $feedbackRepository->findAll();
        $activities = $activityRepository->findBy(array(), array('activityDate' => 'ASC'));
        $form = $this->createForm(ContactType::class, new Contact());
        
        return $this->render('frontend/home.html.twig', [ 'contactForm' => $form->createView(), 'feedbacks' => $feedbacks, 'activities' => $activities, 'team' => $team]);
    }
    
    /**    
    * @Route("/contactSend", name="contactSend", methods={"POST"})
    */
    public function contactSend(Request $request, MailerInterface $mailer): JsonResponse
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {                                    
            $ip = null;
            $contact->setIp($ip);
                        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            
            // Send mails to company
            $company = $entityManager->getRepository(\App\Entity\Company::class)->getMain();
            
            $email = (new TemplatedEmail())
                ->from(new Address($company->getEmail(), $company->getName()))
                ->to(new Address($company->getEmail(), $company->getName()))
                ->subject('[MQP] Nuevo contacto recibido')           
                ->htmlTemplate('emails/admin/newContactCompany.html.twig')
                ->context(['contact' => $contact]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // die($e->getMessage());
            }
            
            // Copy to masquepalabrasoratoria@gmail.com
            $email = (new TemplatedEmail())
                ->from(new Address($company->getEmail(), $company->getName()))
                ->to(new Address('masquepalabrasoratoria@gmail.com', 'MQP'))
                ->subject('[MQP] Nuevo contacto recibido')           
                ->htmlTemplate('emails/admin/newContactCompany.html.twig')
                ->context(['contact' => $contact]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // die($e->getMessage());
            }
            
            // Send mails to client
            $email = (new TemplatedEmail())
                ->from(new Address($company->getEmail(), $company->getName()))
                ->to(new Address($contact->getEmail(), $contact->getName()))
                ->subject('[MQP] Gracias por contactar con Más que Palabras')       
                ->htmlTemplate('emails/admin/newContactClient.html.twig')
                ->context(['contact' => $contact]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // die($e->getMessage());
            }
            
            
            return new JsonResponse(true);
        }
        
        throw new \Exception('Something went wrong!');
    }
    
    /**    
    * @Route("/newPublicUserSend", name="newPublicUserSend", methods={"POST"})
    */
    public function newPublicUserSend(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder, MailerInterface $mailer): JsonResponse
    {
        $report = array('error' => false);
        
        // Check mandatory fields
        $name = $request->request->get('id-signup-name');
        $mail = $request->request->get('id-signup-email');
        $password = $request->request->get('id-signup-password');
        $password2 = $request->request->get('id-signup-password2');
        $agree = $request->request->get('id-signup-agree');
        
        // All fields filled?
        if(!$name || !$mail || !$password || !$password2){
            $report['error'] = true;
            $report['errorDescription'] = "Todos los campos son obligatorios";
            return new JsonResponse($report); 
        }
        
        // Valid mail?
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $report['error'] = true;
            $report['errorDescription'] = "El e-mail introducido no es válido";
            return new JsonResponse($report);            
        }
        
        // Valid password?
        if(strlen($password) < 8){
            $report['error'] = true;
            $report['errorDescription'] = "La contraseña tiene que tener al menos 8 caracteres";
            return new JsonResponse($report);            
        }
        if($password != $password2){
            $report['error'] = true;
            $report['errorDescription'] = "Las contraseñas no coinciden";
            return new JsonResponse($report);            
        }
        
        // Accept legal?
        if(!$agree){
            $report['error'] = true;
            $report['errorDescription'] = "Debes aceptar los términos de uso";
            return new JsonResponse($report);            
        }

        // Exist user?
        $skipCreation = false;
        $newUser = $userRepository->findOneBy(array('email'=>$mail));
        if($newUser){
            // Check if user is same e-mail and validated
            if($newUser->getEmail() == $mail && $newUser->getStatus() == 0){
                $skipCreation = true;
            }else{
                $report['error'] = true;
                $report['errorDescription'] = "Ya existe un usuario con ese correo electrónico";
                return new JsonResponse($report);
            }                        
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        if(!$skipCreation){
            // Create user
            $newUser = new User();
            $newUser->setName($name);
            $newUser->setEmail($mail);
            $newUser->setPassword($passwordEncoder->encodePassword($newUser, $password));         
            $newUser->setRoles(array('ROLE_STUDENT'));
            $newUser->setStatus(0);

            $entityManager->persist($newUser);
            $entityManager->flush();
        }
                
        // Send mail to student     
        $company = $entityManager->getRepository(\App\Entity\Company::class)->getMain();
        
        $timestamp = time();
        $token = md5($newUser->getId() . $newUser->getEmail() . $timestamp . '-_-') . "!==!" . base64_encode($timestamp) . "!==!" . $newUser->getId();  
        
        $email = (new TemplatedEmail())
            ->from(new Address($company->getEmail(), $company->getName()))
            ->to(new Address($newUser->getEmail(), $newUser->getName()))
            ->subject('[MQP] Valida tu corrreo para crear tu cuenta en MásQuePalabras')       
            ->htmlTemplate('emails/admin/newStudentClient.html.twig')
            ->context(['user' => $newUser, 'hash'=>$token]);

        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // die($e->getMessage());
        }
        
        return new JsonResponse($report);
    }
    
    /**    
    * @Route("/validate/{token}", name="validateUser")
    */    
    public function validateUser(Request $request, $token): Response
    {   
        // Get the user
        $em = $this->getDoctrine()->getManager();
        
        // Get token for retrieve user data and date of request
        $error = array('error'=>true, 'errorDescription'=>'Los datos para determinar tu usuario no son correctos'); // Token error, token malformatted or no token.
        $done = false;
        
        $user = null;
                
        if($token){            
            list($userMD5, $date, $userId) = explode('!==!', $token);
            $date = base64_decode($date);

            if(isset($userMD5) && isset($date) && is_numeric($date)){                                
                $user = $em->getRepository(User::class)->find($userId);
                if($user && md5($user->getId() . $user->getEmail() . $date . '-_-') != $userMD5) $user = null;                                                            

                if($user){
                    $error = NULL;
                    $user->setStatus(1);
                    $em->flush();                                    
                }else{
                    $error = array('error'=>true, 'errorDescription'=>'Los datos para determinar tu usuario no son correctos'); // no user found
                }                
            }
        }
        
        return $this->render('security/validated.html.twig', ['error'=>$error]);
    }
    
    /**    
    * @Route("/privacy", name="privacy")
    */    
    public function privacy(Request $request): Response
    {        
        return $this->render('frontend/legal.html.twig', ['param'=>'PRIVACY_TEXT']);
    }
    
    /**    
    * @Route("/terms", name="terms")
    */    
    public function terms(Request $request): Response
    {        
        return $this->render('frontend/legal.html.twig', ['param'=>'TERMS_TEXT']);
    }
    
    /**    
    * @Route("/cookies", name="cookies")
    */    
    public function cookies(Request $request): Response
    {        
        return $this->render('frontend/legal.html.twig', ['param'=>'COOKIE_TEXT']);
    }
}
