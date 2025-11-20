<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OeuvreFrontController extends AbstractController
{
    #[Route('/galerie', name: 'app_oeuvres_gallery')]
    public function gallery(EntityManagerInterface $em): Response
    {
        $oeuvres = $em->getRepository(Oeuvre::class)->findAll();
        $categories = $em->getRepository(Categorie::class)->findAll();
        
        $totalOeuvres = count($oeuvres);

        return $this->render('oeuvre/gallery.html.twig', [
            'oeuvres' => $oeuvres,
            'categories' => $categories,
            'total_oeuvres' => $totalOeuvres,
        ]);
    }

    #[Route('/galerie/categorie/{id}', name: 'app_oeuvres_by_category')]
    public function byCategory(Categorie $categorie, EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Categorie::class)->findAll();
        $oeuvres = $categorie->getOeuvres()->toArray();
        
        $totalOeuvres = count($oeuvres);

        return $this->render('oeuvre/gallery.html.twig', [
            'oeuvres' => $oeuvres,
            'categories' => $categories,
            'total_oeuvres' => $totalOeuvres,
            'current_category' => $categorie,
        ]);
    }

    #[Route('/oeuvre/{id}', name: 'app_oeuvre_details')]
    public function details(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/details.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }
}