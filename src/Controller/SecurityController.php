<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/admin/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('adminIndex');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/admin/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    
    /**
     * @Route("/admin/changePasswordRequest", name="app_changePasswordRequest")
     */
    public function changePasswordRequest(Request $request): JsonResponse
    {
        $user = null;
        $email = $request->request->get('mail');
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByEmail($email);
        
        if($user){
            return $this->forward('App\Controller\Admin\UserController::sendRestorePasswordMail', ['id'  => $user->getId() ]);
        }
        
        return new JsonResponse(['error' => false]);
    }
    
    /**
     * @Route("/admin/changePassword", name="app_changePassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        
        // Get token for retrieve user data and date of request
        $error = array('error'=>true, 'errorDescription'=>'Los datos para determinar tu usuario no son correctos'); // Token error, token malformatted or no token.
        $done = false;
        
        $token = $request->get('i');
        if($token){
            list($userMD5, $date, $userId) = explode('!==!', $token);
            $date = base64_decode($date);

            if(isset($userMD5) && isset($date) && is_numeric($date)){
                // Request is valid for date greater than 24 hours (86400 seconds) ago now.
                if($date <= time() && $date > (time() - 86400)){     
                    $user = $em->getRepository(User::class)->find($userId);
                    if($user && md5($user->getId() . $user->getEmail() . $date . '-_-') != $userMD5) $user = null;                                                            

                    if($user){
                        $error = NULL;
                    }else{
                        $error = array('error'=>true, 'errorDescription'=>'Los datos para determinar tu usuario no son correctos'); // no user found
                    }
                }else{
                    $error = array('error'=>true, 'errorDescription'=>'La petición de cambio de clave realizada el ' . date('d/m/Y H:i', $date) . ' ha expirado', 'date'=>$date); // The change password request have expired.
                }
            }
        }
     
        $form = NULL;
        if(!$error && $user){
            $form = $this->createFormBuilder($user)
                ->add('password', RepeatedType::class, [
			'type' => PasswordType::class,
			'invalid_message' => 'Las claves deben coincidir.',
			'options' => array('attr' => array('class' => 'form-control-lg pr-4 shadow-none')),
			'required' => true,
			'first_options'  => array('label' => 'Contraseña'),
			'second_options' => array('label' => 'Confirma contraseña')])
                ->getForm();
            
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {                
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                ));                
                
                $em->flush();
                $done = true;
            }
        }

        return $this->render('security/changePassword.html.twig', ['form' => (($form) ? $form->createView() : NULL), 'error' => $error, 'done' => $done]);
    }
}
