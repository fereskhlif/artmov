<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard1', name: 'app_dashboard1')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $totalUsers = count($userRepository->findAll());
        $artists = $userRepository->findArtists();
        $activeUsers = $userRepository->findActiveUsers();

        return $this->render('dashboard/index.html.twig', [
            'totalUsers' => $totalUsers,
            'artists' => $artists,
            'activeUsers' => count($activeUsers),
        ]);
    }

    #[Route('/artist-space', name: 'app_artist_space')]
    #[IsGranted('ROLE_ARTISTE')]
    public function artistSpace(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->render('dashboard/artist_space.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/client-space', name: 'app_client_space')]
    #[IsGranted('ROLE_CLIENT')]
    public function clientSpace(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->render('dashboard/client_space.html.twig', [
            'user' => $user
        ]);
    }
}
