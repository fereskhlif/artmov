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
            'formVehicule' => $for->createView(),
            'vehicule' => $vehicule,
        ]);

    }

    #[Route('/updatevehicule/{id}', name: 'app_vehicule_update')]
    public function updateVehicule(Request $request, ManagerRegistry $doctrine, $id)
    {
        $vehicule = $doctrine->getRepository(Vehicule::class)->find($id);
        if (!$vehicule) {
            throw $this->createNotFoundException('Véhicule introuvable');
        }
        $for =$this->createform(VehiculeType::class, $vehicule);
        $for->handleRequest($request);
        if ($for->isSubmitted() && $for->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($vehicule);
            $em->flush();
            return $this->redirectToRoute('liste_vehicule');
        }
        return $this->render('vehicule/update_vehicule.html.twig', ["formvehicule"=>$for->createView()]);
    }
    #[Route('/deletevehicule/{id}', name: 'app_vehicule_delete')]
    public function deleteVehicule(ManagerRegistry $doctrine, $id, VehiculeRepository $rep)
    {
        $vehicule = $rep->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException('Vehicule non trouvé.');
        }

        // Vérification si le véhicule est lié à des trajets
        if (count($vehicule->getTrajets()) > 0)

        {
            $this->addFlash('error', 'Impossible de supprimer ce véhicule : il est lié à un ou plusieurs trajets.');
            return $this->redirectToRoute('liste_vehicule');
        }

        $em = $doctrine->getManager();
        $em->remove($vehicule);
        $em->flush();

        return $this->redirectToRoute('liste_vehicule');
    }

}
