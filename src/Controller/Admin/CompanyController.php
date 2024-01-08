<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/company")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/", name="company_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {           
        $company = $companyRepository->getMain();
        if(!$company){
            // Set main company in database
            $company = new Company();
            $company->setName("Más que Palabras")->setEmail("info@masquepalabrasoratoria.es")->setPhone("952146512");
                        
            $this->getDoctrine()->getManager()->persist($company);
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render('admin/company/index.html.twig', [
            'company' => $company,
        ]);
    }  

    /**
     * @Route("/{id}/edit", name="company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {                        
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', "<strong class='text-success'>Empresa actualizada</strong><br><span class='text-dark-tp4 text-110'>¡Tu cambios se han guardado correctamente!</span>");            
            return $this->redirectToRoute('company_index');
        }

        return $this->render('admin/company/edit.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
        ]);
    }
}