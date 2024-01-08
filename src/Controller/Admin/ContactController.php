<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
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

/**
 * @Route("/admin/contact")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/{page}", name="contact_index", defaults={"page": "1"}, methods={"GET"})
     */
    public function index($page, ContactRepository $contactRepository): Response
    {        
        $nbRegPage = 20;
        
        $contactTotal = $contactRepository->count([]);          
        $contacts = $contactRepository->findBy(array(), array('createDate' => 'DESC'), $nbRegPage, (($page - 1)*$nbRegPage));
        $isLastPage = ((($page - 1)*$nbRegPage) + $nbRegPage) >= $contactTotal;
        
        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contacts,
            'total' => $contactTotal,
            'currPage' => $page,
            'lastPage' => $isLastPage 
        ]);
    }
    
    /**
     * @Route("/{id}/delete", name="contact_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($contact);
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/{id}/review", name="contact_review", methods={"POST"})
     */
    public function review(Request $request, Contact $contact): JsonResponse
    {
        $contact->setReviewedBy($this->getUser());
        $contact->setStatus(1);
        $entityManager = $this->getDoctrine()->getManager();        
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
}