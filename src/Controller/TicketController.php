<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\Event;

use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/ticket')]
final class TicketController extends AbstractController
{
    #[Route('/{eventId}/tickets', name: 'app_event_tickets', methods: ['GET'])]
    public function showEventTickets(
        int $eventId,
        TicketRepository $ticketRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        PaginatorInterface $paginator
    ): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        $query = $ticketRepository->findByEventQuery($eventId);

        $tickets = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ticket/index.html.twig', [
            'event' => $event,
            'tickets' => $tickets,
        ]);
    }

    // Create a new ticket (for event reservation)
    // Create a new ticket (for event reservation)
    #[Route('/new/{eventId}', name: 'app_ticket_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $eventId): Response
    {
        $event = $entityManager->getRepository(Event::class)->find($eventId);

        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }

        // Create a new ticket
        $ticket = new Ticket();
        $ticket->setEvent($event); // Associate the ticket with the event
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculate the total price (prixtot) as event price * quantity
            $prixtot = $event->getPrix() * $ticket->getQuantity();
            $ticket->setPrixtot($prixtot); // Set the calculated total price

            // Save the ticket to the database
            $entityManager->persist($ticket);
            $entityManager->flush();

            $this->addFlash('success', 'Ticket reserved successfully!');
            return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_ticket_show', methods: ['GET'])]
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    // Edit a ticket (for event reservation)
    #[Route('/{id}/edit', name: 'app_ticket_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Recalculate the total price (prixtot) after editing
            $event = $ticket->getEvent();  // Get the associated event
            $prixtot = $event->getPrix() * $ticket->getQuantity(); // Recalculate total price
            $ticket->setPrixtot($prixtot); // Update the prixtot value

            // Save the updated ticket to the database
            $entityManager->flush();

            $this->addFlash('success', 'Ticket updated successfully!');
            return $this->redirectToRoute('app_event_tickets', ['eventId' => $event->getId()]);
        }

        return $this->render('ticket/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    // Delete a ticket
    #[Route('/{id}', name: 'app_ticket_delete', methods: ['POST'])]
    public function delete(Request $request, Ticket $ticket, EntityManagerInterface $entityManager): Response
    {
        // Validate CSRF token before deletion
        if ($this->isCsrfTokenValid('delete' . $ticket->getId(), $request->request->get('_token'))) {
            // Remove the ticket from the database
            $entityManager->remove($ticket);
            $entityManager->flush();

            $this->addFlash('success', 'Ticket deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        // Redirect to the event tickets list after deletion
        return $this->redirectToRoute('app_event_tickets', ['eventId' => $ticket->getEvent()->getId()]);
    }
}
