<?php



namespace App\Controller;
use App\Entity\Vente;
use App\Repository\VenteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;   
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;     

final class VenteController extends AbstractController
{
  #[Route('/vente', name: 'app_ventes')]
  public function index(VenteRepository $venteRepository): Response
  {
      $ventes = $venteRepository->findAll();
      return $this->render('vente/index.html.twig', [
          'ventes' => $ventes,
      ]);
  }
  


  #[Route('/ventes/{id}', name: 'app_ventes_show', methods: ['GET'], requirements: ['id' => '\\d+'])]
  public function show(#[MapEntity(mapping: ['id' => 'Id_vente'])] Vente $vente): Response
  {
      return $this->render('vente/show.html.twig', [
          'vente' => $vente,
      ]);
  }


  #[Route('/ventes/new', name: 'app_ventes_new')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $vente = new Vente();

    $form = $this->createFormBuilder($vente)
        ->add('Date_vente', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Date de vente',
        ])
        ->add('montant', NumberType::class, [
            'label' => 'Montant',
            'scale' => 2,
        ])
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->persist($vente);
        $em->flush();

        return $this->redirectToRoute('app_ventes');
    }

    return $this->render('vente/new.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/ventes/{id}/edit', name: 'app_ventes_edit', methods: ['GET','POST'], requirements: ['id' => '\\d+'])]
public function edit(Request $request, #[MapEntity(mapping: ['id' => 'Id_vente'])] Vente $vente, EntityManagerInterface $em): Response
{
    $form = $this->createFormBuilder($vente)
        ->add('Date_vente', DateTimeType::class, ['widget' => 'single_text'])
        ->add('montant', NumberType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        return $this->redirectToRoute('app_ventes');
    }

    return $this->render('vente/edit.html.twig', [
        'vente' => $vente,
        'form' => $form->createView(),
    ]);
}



#[Route('/ventes/{id}/delete', name: 'app_ventes_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
public function delete(Request $request, #[MapEntity(mapping: ['id' => 'Id_vente'])] Vente $vente, EntityManagerInterface $em): RedirectResponse
{
    if ($this->isCsrfTokenValid('delete'.$vente->getIdVente(), $request->request->get('_token'))) {
        $em->remove($vente);
        $em->flush();
        $this->addFlash('success', 'Vente supprimée avec succès.');
    }

    return $this->redirectToRoute('app_ventes');
}

  

    
}
