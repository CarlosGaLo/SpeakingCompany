<?php

namespace App\Controller\Admin;

use App\Repository\FeedbackRepository;
use App\Repository\ActivityRepository;
use App\Repository\WebsiteParameterRepository;

use App\Entity\WebsiteParameter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/website") 
 */
class WebsiteController extends AbstractController {
    
    /**    
    * @Route("/index/{tab}", name="website_index", defaults={"tab": "general"}, requirements={"tab": "general|feedback|activity|legal"})
    */
    public function index($tab, FeedbackRepository $feedbackRepository, ActivityRepository $activityRepository): Response
    {         
        $activities = $activityRepository->findBy(array(), array('activityDate' => 'ASC'));
        
        return $this->render('admin/website/index.html.twig', ['tab'=>$tab, 'feedbacks' => $feedbackRepository->findAll(), 'activities' => $activities]);
    }

    /**
     * @Route("/set/{param}", name="website_setParam", methods={"POST"})
     */
    public function setWebsiteParam(Request $request, $param, WebsiteParameterRepository $websiteParamRespository): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $value = $request->request->get('value');
        
        $paramObj = $websiteParamRespository->findOneBy(['name'=>$param]);
        if(!$paramObj){
            $paramObj = new WebsiteParameter();
            $paramObj->setName($param);
            $entityManager->persist($paramObj);
        }
        
        $paramObj->setValue($value);
            
        $entityManager->flush();        

        return new JsonResponse(true);
    }
    
    /**
     * @Route("/setDefault/{param}", name="website_setParamDefault", methods={"POST"})
     */
    public function setWebsiteParamDefault(Request $request, $param, WebsiteParameterRepository $websiteParamRespository): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $value = $request->request->get('value');
        
        $paramObj = $websiteParamRespository->findOneBy(['name'=>$param]);
        if(!$paramObj){
            $paramObj = new WebsiteParameter();
            $paramObj->setName($param);
            $entityManager->persist($paramObj);
        }
        
        $paramObj->setDefaultValue($value);
            
        $entityManager->flush();        

        return new JsonResponse(true);
    }
}
