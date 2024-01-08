<?php

namespace App\Controller\Admin;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @Route("/admin") 
 */
class AdminController extends AbstractController {
    
    /**
    * @Route("/")
    * @Route("/index", name="adminIndex")
    */
    public function index(ContactRepository $contactRepository){      
        $validContactNb = $contactRepository->count(array('status'=>1));
        $pendingContactNb = $contactRepository->count(array('status'=>0));
        
        return $this->render('admin/index.html.twig', ['validContactNb' => $validContactNb, 'pendingContactNb' => $pendingContactNb]);
    }
    
    public function layoutHeader(ContactRepository $contactRepository){
        $contactNb = $contactRepository->count(array('status'=>0));
        $contactLast = $contactRepository->findBy(array('status'=>0), array('createDate' => 'DESC'), 3);
        return $this->render('admin/layout/header.html.twig', array('contactNb' => $contactNb, 'contactLast' => $contactLast));
    }
    
    public function layoutSidebar(Request $request, $section, $page, ContactRepository $contactRepository){
        $contactNb = $contactRepository->count(array('status'=>0));
        return $this->render('admin/layout/sidebar.html.twig', array('section' => $section, 'page' => $page, 'contactNb' => $contactNb));
    }
       
    /**
     *
     * @Route("/command/cache/clear", name="command_cache_clear")
     */
    public function command_cache_clear(KernelInterface $kernel)
    {
        return $this->do_command($kernel, 'cache:clear');
    }

    /**
     *
     * @Route("/command/cache/warmup", name="command_cache_warmup")
     */
    public function command_cache_warmup(KernelInterface $kernel)
    {
        return $this->do_command($kernel, 'cache:warmup');
    }

    private function do_command($kernel, $command)
    {
        $env = $kernel->getEnvironment();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => $command,
            '--env' => $env
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }
}
