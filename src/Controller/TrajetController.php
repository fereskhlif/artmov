<?php

namespace App\Controller;
use App\Form\TrajetType;

use App\Entity\Trajet;
use App\Repository\TransportRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TrajetController extends AbstractController
{
    #[Route('/trajet', name: 'app_trajet')]
    public function index(): Response
    {
        return $this->render('trajet/index.html.twig', [
            'controller_name' => 'TrajetController',
        ]);
    }
    #[Route('/listetrajet', name: 'liste_trajet')]
    public function listeTrajet(TransportRepository $transportRepository)
    {
        $trajet=$transportRepository->findAll();
        return $this->render('trajet/liste.html.twig', ["trajet"=>$trajet]);
    }
    #[Route('/addtrajet', name: 'app_trajet_add')]
public function addtrajet(Request $request,ManagerRegistry $doctrine)
    {
        $trajet=new Trajet();
        $for=$this->createForm(TrajetType::class,$trajet);
        $for->handleRequest($request);
        if($for->isSubmitted() && $for->isValid()){
            $em=$doctrine->getManager();
            $em->persist($trajet);
            $em->flush();
            return $this->redirectToRoute('app_trajet_add');
        }
        return $this->render('trajet/addtrajet.html.twig', ["trajet"=>$for->createView()]);
    }

}
