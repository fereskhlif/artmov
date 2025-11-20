<?php

namespace App\Controller;

use App\Entity\Oeuvre;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Ajoutez cette ligne

class OeuvreController extends AbstractController
{
    #[Route('/oeuvres', name: 'app_oeuvres')]
    public function index(EntityManagerInterface $em): Response
    {
        $oeuvres = $em->getRepository(Oeuvre::class)->findAll();
        
        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvres,
        ]);
    }

    #[Route('/oeuvres/{id}', name: 'app_oeuvres_show', methods: ['GET'], requirements: ['id' => '\\d+'])]
    public function show(Oeuvre $oeuvre): Response
    {
        return $this->render('oeuvre/show.html.twig', [
            'oeuvre' => $oeuvre,
        ]);
    }

    #[Route('/oeuvres/new', name: 'app_oeuvres_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $oeuvre = new Oeuvre();

        $form = $this->createFormBuilder($oeuvre)
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
            ])
            ->add('image', TextType::class, [
                'label' => 'Image (URL)',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($oeuvre);
            $em->flush();

            return $this->redirectToRoute('app_oeuvres');
        }

        return $this->render('oeuvre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/oeuvres/{id}/edit', name: 'app_oeuvres_edit', methods: ['GET','POST'], requirements: ['id' => '\\d+'])]
    public function edit(Request $request, Oeuvre $oeuvre, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder($oeuvre)
            ->add('titre', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'scale' => 2,
            ])
            ->add('image', TextType::class, [
                'label' => 'Image (URL)',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_oeuvres');
        }

        return $this->render('oeuvre/edit.html.twig', [
            'oeuvre' => $oeuvre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/oeuvres/{id}/delete', name: 'app_oeuvres_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function delete(Request $request, Oeuvre $oeuvre, EntityManagerInterface $em): RedirectResponse
    {
        if ($this->isCsrfTokenValid('delete'.$oeuvre->getId(), $request->request->get('_token'))) {
            $em->remove($oeuvre);
            $em->flush();
            $this->addFlash('success', 'Œuvre supprimée avec succès.');
        }

        return $this->redirectToRoute('app_oeuvres');
    }
}