<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Repository\VehiculeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VehiculeController extends AbstractController
{
    #[Route('/vehicule', name: 'app_vehicule')]
    public function index(): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'controller_name' => 'VehiculeController',
        ]);
    }

    #[Route('/liste_vehicule', name: 'liste_vehicule')]
    public function listeVehicule(VehiculeRepository $vehiculeRepository)
    {
        $vehicule = $vehiculeRepository->findAll();
        return $this->render('vehicule/liste_vehicule.html.twig', ["vehicule" => $vehicule]);
    }

    #[Route('/addvehicule', name: 'app_vehicule_add')]
    public function addVehicule(Request $request, ManagerRegistry $doctrine)
    {
        $vehicule = new Vehicule();
        $for = $this->createForm(VehiculeType::class, $vehicule);
        $for->handleRequest($request);

        if ($for->isSubmitted() && $for->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($vehicule);
            $em->flush();

            return $this->redirectToRoute('liste_vehicule');
        }

        return $this->render('vehicule/add_vehicule.html.twig', [
            'formVehicule' => $for->createView()
        ]);
    }
}
