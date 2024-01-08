<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Entity\WebsiteParameter;

class AppExtension extends AbstractExtension
{
    private $em;
    
    public function __construct(EntityManagerInterface $entityManager) {
        $this->em = $entityManager;
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('aproxDate', [$this, 'getAproxDate']),
            new TwigFilter('websiteParam', [$this, 'getWebsiteParam']),
        ];
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('getCompany', [$this, 'getCompany']),
        ];
    }

    public function getCompany(): Company
    {   
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->em;
        $company = $em->getRepository(Company::class)->getMain();        
        
        return $company;
    }
    
    public function getWebsiteParam($paramName)
    {   
        $paramValue = null;
        
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->em;
        $param = $em->getRepository(WebsiteParameter::class)->findOneBy(['name'=>$paramName]);
        if($param) $paramValue = $param->getValue();
        
        return $paramValue;
    }
    
    public function getAproxDate(\DateTime $date){
        $currDate = new \DateTime();
        if($currDate->format('YmdHi') - $date->format('YmdHi') < 10){
            return "Hace un momento";
        }elseif($currDate->format('YmdHi') - $date->format('YmdHi') < 60){
            return "Hace " . ($currDate->format('YmdHi') - $date->format('YmdHi')) . " minutos";
        }elseif($date->format('Ymd') == $currDate->format('Ymd')){
            return $date->format('H:i') . " h";
        }else{        
            return $date->format('d/m/y H:i') . " h";
        }
    }
}