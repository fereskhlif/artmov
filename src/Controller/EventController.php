<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


#[Route('/event')]
final class EventController extends AbstractController
{
    #[Route(name: 'app_event_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/users',name: 'app_event_index_users', methods: ['GET'])]
    public function indexfront(EventRepository $eventRepository): Response
    {
        return $this->render('event/index_front.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_event_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the image upload
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Get the original filename
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Replace non-ASCII characters and spaces with safe equivalents
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFilename);
                // Generate a unique filename for the uploaded file
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Try to move the uploaded file to the directory
                try {
                    $imageFile->move(
                        $this->getParameter('event_images_directory'), // You must define this parameter in `services.yaml`
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception if something goes wrong
                    $this->addFlash('error', 'There was an error uploading the image.');
                    return $this->redirectToRoute('app_event_new');
                }

                // Set the image path in the event entity
                $event->setImage($newFilename);
            }

            // Persist the event to the database
            $entityManager->persist($event);
            $entityManager->flush();

            // Redirect to the event index page after successful creation
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_event_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        // Create form for the event
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Handle image file upload if it's set
            $imageFile = $form->get('image')->getData();
            
            if ($imageFile) {
                // Get the original filename and create a safe version
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Replace non-ASCII characters and spaces with safe equivalents
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFilename);
                // Generate a unique filename for the uploaded file
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    // Move the file to the directory where images are stored
                    $imageFile->move(
                        $this->getParameter('event_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the image.');
                    return $this->redirectToRoute('app_event_edit', ['id' => $event->getId()]);
                }

                // Set the new image filename on the event entity
                $event->setImage($newFilename);
            }

            // Persist changes to the database
            $entityManager->flush();

            // Redirect to the event list after successful edit
            return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
        }

        // Render the edit form
        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_event_delete', methods: ['POST'])]
public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
{
    // Check if the CSRF token is valid
    if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
        $entityManager->remove($event);
        $entityManager->flush();
        $this->addFlash('success', 'Event deleted successfully.');
    } else {
        $this->addFlash('error', 'Invalid request.');
    }

    return $this->redirectToRoute('app_event_index', [], Response::HTTP_SEE_OTHER);
}

}
