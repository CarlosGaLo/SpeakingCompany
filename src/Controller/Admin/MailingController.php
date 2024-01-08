<?php

namespace App\Controller\Admin;

use App\Entity\MailingElement;
use App\Repository\MailingElementRepository;
use App\Entity\Mailing;
use App\Repository\MailingRepository;
use App\Entity\MailingElementRol;
use App\Repository\MailingElementRolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Route("/admin/website/mailing")
 */
class MailingController extends AbstractController
{    
    /**
     * @Route("/", name="mailing_index", methods={"GET"})
     */
    public function index(MailingRepository $mailingRepository): Response
    {
        return $this->render('admin/mailing/index.html.twig', [
            'mailings' => $mailingRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/newMailing", name="mailing_newMailing", methods={"GET","POST"})
     */
    public function new(Request $request, MailingElementRepository $mailingEleRepository, MailingElementRolRepository $mailingEleRolRepository): Response
    {                
        $mailElements = $mailingEleRepository->findBy(array(), array('testMail' => 'DESC', 'mail' => 'asc'));
        $mailElementRoles = $mailingEleRolRepository->findBy(array(), array('name' => 'ASC'));
        
        return $this->render('admin/mailing/newMailing.html.twig', [
            'mailElements' => $mailElements,
            'mailElementRoles' => $mailElementRoles,
        ]);
    } 
    
    /**
     * @Route("/submitMailingElement", name="mailing_submitMailingElement", methods={"POST"})
     */
    public function submitMailingElement(Request $request, MailingElementRepository $mailingEleRepository, MailingElementRolRepository $mailingEleRolRepository): JsonResponse
    {                        
        // Get data
        $elementId = $request->request->get('id');
        $elementName = $request->request->get('name');
        $elementSurname1 = $request->request->get('surname1');
        $elementSurname2 = $request->request->get('surname2');
        $elementMail = $request->request->get('mail');                        
        $elementPhone = $request->request->get('phone');                        
        $elementLastCourse = $request->request->get('lastCourse');
        $elementOrigin = $request->request->get('origin');
        $elementBirthday = $request->request->get('birthday');
        $elementTypeRol = $request->request->get('rolType');
        $elementTestMail = $request->request->get('testMail');
        
        if($elementBirthday){
            // Clear birthday
            try {
                $elementBirthday = \DateTime::createFromFormat('Y-m-d', $elementBirthday);
            } catch (\Exception $e) { 
                $elementBirthday = null;
            }
        }else{
            $elementBirthday = null;
        }
        
        $typeRol = null;
        if($elementTypeRol){
            $typeRol = $mailingEleRolRepository->find($elementTypeRol);
        }
        
        // Persist data
        $entityManager = $this->getDoctrine()->getManager();
        if($elementId){
            // Update data
            $myElement = $mailingEleRepository->findOneBy(['id'=>$elementId]);
            if($myElement){
                $myElement->setName($elementName);
                $myElement->setSurname($elementSurname1);
                $myElement->setSurname2($elementSurname2);
                $myElement->setMail($elementMail);
                $myElement->setPhone($elementPhone);
                $myElement->setLastCourse($elementLastCourse);
                $myElement->setOrigin($elementOrigin);                
                $myElement->setBirthday($elementBirthday);
                $myElement->setRolType($typeRol);
                $myElement->setTestMail($elementTestMail);   
            }            
        }else{
            // New data
            $newElement = new MailingElement();
            $newElement->setName($elementName);
            $newElement->setSurname($elementSurname1);
            $newElement->setSurname2($elementSurname2);
            $newElement->setMail($elementMail);
            $newElement->setPhone($elementPhone);
            $newElement->setLastCourse($elementLastCourse);
            $newElement->setOrigin($elementOrigin);
            $newElement->setBirthday($elementBirthday);
            $newElement->setTestMail($elementTestMail);   
            
            $entityManager->persist($newElement);            
        }
        $entityManager->flush();
        
        // Return info  
        $mailElements = $mailingEleRepository->findAll();
        $list = $this->renderView('admin/mailing/mailingElementList.html.twig', [
            'mailElements' => $mailElements,
        ]);

        return new JsonResponse(['list' => $list]);
    }
    
    /**
     * @Route("/deleteMailingElement/{id}", name="mailing_deleteMailingElement", methods={"POST"})
     */
    public function deleteMailingElement(Request $request, MailingElement $mailingelement): JsonResponse
    {                        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mailingelement);
        $entityManager->flush();

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/submitMailingElementRol", name="mailing_submitMailingElementRol", methods={"POST"})
     */
    public function submitMailingElementRol(Request $request, MailingElementRolRepository $mailingEleRolRepository): JsonResponse
    {                        
        // Get data
        $elementId = $request->request->get('id');
        $elementName = $request->request->get('name');                        
        
        // Persist data
        $entityManager = $this->getDoctrine()->getManager();
        if($elementId){
            // Update data
            $myElement = $mailingEleRolRepository->findOneBy(['id'=>$elementId]);
            if($myElement){
                $myElement->setName($elementName);                
            }            
        }else{
            // New data
            $newElement = new MailingElementRol();
            $newElement->setName($elementName);            
            
            $entityManager->persist($newElement);            
        }
        $entityManager->flush();
        
        // Return info  
        $mailElementRoles = $mailingEleRolRepository->findAll();        
        
        $rolData = array();
        foreach ($mailElementRoles as $mailElementRol){
            $mailRol = array('id' => $mailElementRol->getId(), 'name' => $mailElementRol->getName(), 'deleteUrl' => $this->generateUrl('mailing_deleteMailingElementRol', array('id'=>$mailElementRol->getId())));
            $rolData[] = $mailRol;
        }

        return new JsonResponse(['rolData' => $rolData]);
    }
    
    /**
     * @Route("/deleteMailingElementRol/{id}", name="mailing_deleteMailingElementRol", methods={"POST"})
     */
    public function deleteMailingElementRol(Request $request, MailingElementRol $mailingElementRol, MailingElementRolRepository $mailingEleRolRepository, MailingElementRepository $mailingEleRepository): JsonResponse
    {                                        
        $entityManager = $this->getDoctrine()->getManager();
        
        $dqlSetMailElementRolNull = 'UPDATE App\Entity\MailingElement me SET me.rolType = NULL WHERE me.rolType = :deleteRolType';
        $entityManager->createQuery($dqlSetMailElementRolNull)->execute(['deleteRolType' => $mailingElementRol]);
        
        $entityManager->remove($mailingElementRol);
        $entityManager->flush();
        
        // Return info  
        $mailElementRoles = $mailingEleRolRepository->findAll();
        
        $rolData = array();
        foreach ($mailElementRoles as $mailElementRol){
            $mailRol = array('id' => $mailElementRol->getId(), 'name' => $mailElementRol->getName(), 'deleteUrl' => $this->generateUrl('mailing_deleteMailingElementRol', array('id'=>$mailElementRol->getId())));
            $rolData[] = $mailRol;
        }

        return new JsonResponse(['rolData' => $rolData]);
    }
    
    /**
     * @Route("/deleteMailing/{id}", name="mailing_deleteMailing", methods={"POST"})
     */
    public function deleteMailing(Request $request, Mailing $mailing): JsonResponse
    {                        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mailing);
        $entityManager->flush();

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/createMailing", name="mailing_create", methods={"POST"})
     */
    public function createMailing(Request $request): JsonResponse
    {                                
        // Get data        
        $subject = $request->request->get('subject');
        $body = $request->request->get('body');
        $toIds = $request->request->get('to');
        
        // Persist data
        $entityManager = $this->getDoctrine()->getManager();
        
        // Create mailing
        $newMailing = new Mailing();
        $newMailing->setSubject($subject);
        $newMailing->setBody($body);
        $newMailing->setToIds($toIds);
        $newMailing->setCreateDate(new \DateTime());
        $newMailing->setFinished(false);
        $newMailing->setProcessing(false);

        $entityManager->persist($newMailing);
        $entityManager->flush();
        
        // Return exit url
        $url = $this->generateUrl('mailing_index');

        return new JsonResponse(['url'=>$url]);
    }
    
    /**
     * @Route("/launchMailing/{id}", name="mailing_launchMailing", methods={"POST"})
     */
    public function launchMailing(Request $request, Mailing $mailing, MailingElementRepository $mailingEleRepository, MailerInterface $mailer): JsonResponse
    {   
        $error = null;
        $finished = false;        
        
        // Get to ids as array
        $toFinal = null;
        $toArray = $mailing->getToIdsArray();        
        if(count($toArray) > 0) $toFinal = $toArray[0];                
        
        // Get mailElement
        $toEntity = $mailingEleRepository->findOneById($toFinal);
        if($toFinal && $toEntity){
            // Prepare mail
            $entityManager = $this->getDoctrine()->getManager();
            $company = $entityManager->getRepository(\App\Entity\Company::class)->getMain();
            
            // Replaced fields
            $finalSubject = $toEntity->replaceText($mailing->getSubject());
            $finalBody = $toEntity->replaceText($mailing->getBody());                        
            
            $email = (new Email())
                ->from(new Address($company->getEmail(), $company->getName()))
                ->to(new Address($toEntity->getMail(), $toEntity->getName()))                
                ->subject($finalSubject)
                ->text(strip_tags($finalBody))
                ->html($finalBody)
                ;
            
            // Launch mail
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                $error = $e->getMessage();
            }
                        
            if (!$error) {
                if (($key = array_search($toFinal, $toArray)) !== false) {
                    unset($toArray[$key]);

                    // Update mailing entity
                    $mailing->setTotalMails($mailing->getTotalMails() + 1);
                    $mailing->setToIds(implode(',', $toArray));
                    
                    if(count($toArray) <= 0){
                        $mailing->setFinished (true);                        
                        $finished = true;
                    }
                    
                    $entityManager->flush();
                }
            }
        }                          
                
        return new JsonResponse(['error' => $error, 'finished' => $finished, 'totalMails' => $mailing->getTotalMails()]);
    }
}