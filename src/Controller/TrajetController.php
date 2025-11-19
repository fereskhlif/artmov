<?php

namespace App\Controller;
use App\Form\TrajetType;
use App\Entity\Vehicule;

use App\Entity\Trajet;
use App\Repository\TrajetRepository;
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

    #[Route('/contact.html', name: 'contact')]

    public function contact(): Response
    {
        return $this->render('trajet/contact.html.twig', []);


    }
    #[Route('/about-us', name: 'app_about_us')]
    public function aboutUs(): Response
    {
        return $this->render('trajet/about.html.twig', []);
    }
    #[Route('/listetrajet', name: 'liste_trajet')]
    public function listeTrajet(TrajetRepository $repository )
    {
        $trajet=$repository->findAll();
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
            return $this->redirectToRoute('liste_trajet');
        }
        return $this->render('trajet/addtrajet.html.twig', ["trajet"=>$for->createView()]);
    }
    #[Route('/trajet/new', name: 'trajet_new')]
    public function neww(Request $request, ManagerRegistry $doctrine, TrajetRepository $trajetRepository): Response
    {
        $trajet = new Trajet();
        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trajets = $trajetRepository->createQueryBuilder('t')
                ->where('t.nbPlaces > 0')
                ->getQuery()
                ->getResult();

            $vehicule = $trajet->getVehicule();
            if ($vehicule) {
                $trajet->setNbPlaces($vehicule->getCapacite());
            }

            $em = $doctrine->getManager();
            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet ajouté avec succès !');
            return $this->redirectToRoute('liste_trajet');
        }

        return $this->render('trajet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/deletetrajet/{id}', name: 'app_trajet_delete')]
public function deletetrajet(TrajetRepository $repository,int $id,ManagerRegistry $doctrine)
    {
        $trajet=$repository->find($id);
        $em=$doctrine->getManager();
        $em->remove($trajet);
        $em->flush();
        return $this->redirectToRoute('liste_trajet');

    }
    #[Route('/updatetrajet/{id}', name: 'app_trajet_update')]
public function updatetrajet(TrajetRepository $repository,int $id,Request $request,ManagerRegistry $doctrine)
    {
        $trajet=$repository->find($id);
        $for=$this->createForm(TrajetType::class,$trajet);
        $for->handleRequest($request);
        if($for->isSubmitted() && $for->isValid()){
            $em=$doctrine->getManager();
            $em->persist($trajet);
            $em->flush();
            return $this->redirectToRoute('liste_trajet');
        }
        return $this->render('trajet/updatetrajet.html.twig', ["trajet"=>$for->createView()]);
    }
    #[Route('/trajet/new', name: 'trajet_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trajet = new Trajet();

        $form = $this->createForm(TrajetType::class, $trajet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Initialiser nb_places avec la capacité du véhicule choisi
            if ($trajet->getVehicule()) {
                $trajet->setNbPlaces($trajet->getVehicule()->getCapacite());
            }

            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Trajet ajouté avec succès !');

            return $this->redirectToRoute('liste_trajet');
        }

        return $this->render('trajet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trajets', name: 'frontoffice_trajets')]
    public function trajets(TrajetRepository $trajetRepository): Response
    {
        $trajets = $trajetRepository->findBy(['statut' => 'Disponible']); // seuls les trajets programmés
        return $this->render('trajet/trajets.html.twig', [
            'trajets' => $trajets
        ]);
    }


    #[Route('/trajet/reserver/{id}', name: 'trajet_reserver')]
    public function reserver(Trajet $trajet, ManagerRegistry $doctrine): Response
    {
        // Vérifier si le trajet n'est pas déjà complet
        if ($trajet->getStatut() === 'Complet') {
            $this->addFlash('error', 'Ce trajet est complet.');
            return $this->redirectToRoute('frontoffice_trajets');
        }

        // Vérifier s'il reste des places
        if ($trajet->getNbPlaces() > 0) {
            // Décrémenter le nombre de places
            $trajet->setNbPlaces($trajet->getNbPlaces() - 1);

            // Mettre à jour le statut si nécessaire
            if ($trajet->getNbPlaces() === 0) {
                $trajet->setStatut('Complet');
            }

            // Persister les changements
            $em = $doctrine->getManager();
            $em->persist($trajet);
            $em->flush();

            $this->addFlash('success', 'Réservation effectuée avec succès ! Il reste ' . $trajet->getNbPlaces() . ' place(s).');
        } else {
            $this->addFlash('error', 'Aucune place disponible pour ce trajet.');
        }

        return $this->redirectToRoute('frontoffice_trajets');
    }

}
