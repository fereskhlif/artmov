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
use App\Repository\TrajetRepository;

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
        $vehicule = $vehiculeRepository->showAllVehiculeByCapacity();
        return $this->render('vehicule/liste_vehicule.html.twig', ["vehicule" => $vehicule]);
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('backend/index2.html.twig');
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

        $em = $doctrine->getManager();
        $em->remove($vehicule);
        $em->flush();

        return $this->redirectToRoute('liste_vehicule');
    }
    #[Route('showBooksByAuthors/{id}', name: 'app_show_books_by_authors')]
public function listetrajetsbyvehicule(TrajetRepository $repo ,$id)
{
    $trajets = $repo->showAlltrajetByVehicule($id);
    return $this->render('vehicule/listetrajetsbyvehicule.html.twig', ["tab" => $trajets]);

}
    #[Route('/vehicules', name: 'app_vehicule_list')]
    public function list(Request $request, VehiculeRepository $repo): Response
    {
        $matricule = $request->query->get('matricule');

        if ($matricule) {
            $vehicules = $repo->findByMatricule($matricule);
        } else {
            $vehicules = $repo->findAll();
        }

        return $this->render('vehicule/liste_vehicule.html.twig', [
            'vehicule' => $vehicules,
            'searchTerm' => $matricule,
        ]);
    }


}
