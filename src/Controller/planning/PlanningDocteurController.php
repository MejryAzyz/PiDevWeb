<?php

namespace App\Controller\planning;

use App\Entity\PlanningDocteur;
use App\Form\PlanningDocteurType;
use App\Repository\PlanningDocteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;
use Mailjet\Client as MailjetClient;
use Mailjet\Resources;
use App\Entity\Reservation;

#[Route('/planning/docteur')]
final class PlanningDocteurController extends AbstractController
{
    private $logger;
    private $twilioClient;
    private $mailjetClient;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        // Initialize Twilio client
        $accountSid = $_ENV['TWILIO_ACCOUNT_SID'];
        $authToken = $_ENV['TWILIO_AUTH_TOKEN'];
        $this->twilioClient = new Client($accountSid, $authToken);

        // Initialize Mailjet client
        $this->mailjetClient = new MailjetClient(
            $_ENV['MAILJET_API_KEY'],
            $_ENV['MAILJET_API_SECRET'],
            true,
            ['version' => 'v3.1']
        );
    }

    private function sendSmsNotification(PlanningDocteur $planningDocteur): void
    {
        try {
            $doctor = $planningDocteur->getDocteur();
            if (!$doctor || !$doctor->getTelephone()) {
                $this->logger->warning('Cannot send SMS: Doctor or phone number not found');
                return;
            }

            // Format the message
            $message = "🔔 Nouveau Planning MedTravel\n\n";
            $message .= "📅 Date: " . $planningDocteur->getDateJour()->format('d/m/Y') . "\n";
            $message .= "⏰ Heure: " . $planningDocteur->getHeureDebut() . " - " . $planningDocteur->getHeureFin() . "\n";
            
            if ($planningDocteur->getDossierMedical()) {
                $message .= "👤 Patient: " . $planningDocteur->getDossierMedical()->getNomPatient() . "\n";
            }

            $message .= "\nMerci de confirmer votre disponibilité.";

            // Send SMS using Twilio
            $this->twilioClient->messages->create(
                $doctor->getTelephone(),
                [
                    'from' => $_ENV['TWILIO_PHONE_NUMBER'],
                    'body' => $message
                ]
            );

            $this->logger->info('SMS notification sent successfully to doctor');
        } catch (\Exception $e) {
            $this->logger->error('Error sending SMS notification: ' . $e->getMessage());
        }
    }

    private function sendEmailNotification(PlanningDocteur $planningDocteur): void
    {
        try {
            $this->logger->info('Starting email notification process');

            // Check if Mailjet credentials are set
            if (!isset($_ENV['MAILJET_API_KEY']) || !isset($_ENV['MAILJET_API_SECRET']) || !isset($_ENV['MAILJET_SENDER_EMAIL'])) {
                $this->logger->error('Mailjet credentials are not properly configured in .env file');
                $this->logger->error('MAILJET_API_KEY: ' . (isset($_ENV['MAILJET_API_KEY']) ? 'set' : 'not set'));
                $this->logger->error('MAILJET_API_SECRET: ' . (isset($_ENV['MAILJET_API_SECRET']) ? 'set' : 'not set'));
                $this->logger->error('MAILJET_SENDER_EMAIL: ' . (isset($_ENV['MAILJET_SENDER_EMAIL']) ? 'set' : 'not set'));
                return;
            }

            $this->logger->info('Mailjet credentials are properly configured');

            $doctor = $planningDocteur->getDocteur();
            if (!$doctor) {
                $this->logger->warning('Cannot send email: Doctor not found');
                return;
            }
            if (!$doctor->getEmail()) {
                $this->logger->warning('Cannot send email: Doctor email not found for doctor: ' . $doctor->getNom());
                return;
            }

            $this->logger->info('Attempting to send email to: ' . $doctor->getEmail());

            // Get the logo file and convert it to base64
            $logoPath = __DIR__ . '/../../../public/images/email/logo.png';
            if (!file_exists($logoPath)) {
                $this->logger->error('Logo file not found at: ' . $logoPath);
                return;
            }

            // Read the logo file
            $logoData = file_get_contents($logoPath);
            if ($logoData === false) {
                $this->logger->error('Failed to read logo file');
                return;
            }

            // Check file size (limit to 50KB)
            if (strlen($logoData) > 51200) { // 50KB in bytes
                $this->logger->error('Logo file is too large: ' . strlen($logoData) . ' bytes');
                return;
            }

            // Convert to base64
            $base64Logo = base64_encode($logoData);
            $logoDataUri = 'data:image/png;base64,' . $base64Logo;

            // Create HTML email content with base64 encoded logo
            $htmlContent = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 5px;">
                <div style="text-align: center; margin-bottom: 30px; padding: 20px 0; background-color: #ffffff;">
                    <img src="' . $logoDataUri . '" alt="MedTravel Logo" style="max-width: 200px; height: auto; margin-bottom: 20px;">
                    <h1 style="color: #2c3e50; margin-bottom: 10px; font-size: 24px;">Nouveau Planning MedTravel</h1>
                    <p style="color: #7f8c8d; font-size: 16px;">Une nouvelle entrée de planning a été ajoutée</p>
                </div>
                
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                    <h2 style="color: #34495e; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 5px; font-size: 20px;">Détails du Planning</h2>
                    <p style="margin: 10px 0; font-size: 16px;"><strong>📅 Date:</strong> ' . $planningDocteur->getDateJour()->format('d/m/Y') . '</p>
                    <p style="margin: 10px 0; font-size: 16px;"><strong>⏰ Heure:</strong> ' . $planningDocteur->getHeureDebut() . ' - ' . $planningDocteur->getHeureFin() . '</p>';
            
            if ($planningDocteur->getDossierMedical()) {
                $htmlContent .= '
                    <p style="margin: 10px 0; font-size: 16px;"><strong>👤 Patient:</strong> ' . $planningDocteur->getDossierMedical()->getNomPatient() . '</p>';
            }
            
            $htmlContent .= '
                </div>
                
                <div style="text-align: center; margin-top: 30px; padding: 20px 0; border-top: 1px solid #e0e0e0;">
                    <p style="color: #7f8c8d; font-size: 16px;">Merci de confirmer votre disponibilité.</p>
                </div>
            </div>';

            // Send email using Mailjet
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => $_ENV['MAILJET_SENDER_EMAIL'],
                            'Name' => "MedTravel"
                        ],
                        'To' => [
                            [
                                'Email' => $doctor->getEmail(),
                                'Name' => $doctor->getNom()
                            ]
                        ],
                        'Subject' => "Nouveau Planning MedTravel - " . $planningDocteur->getDateJour()->format('d/m/Y'),
                        'HTMLPart' => $htmlContent
                    ]
                ]
            ];

            $this->logger->info('Preparing to send email with Mailjet');
            $this->logger->info('From email: ' . $_ENV['MAILJET_SENDER_EMAIL']);
            $this->logger->info('To email: ' . $doctor->getEmail());

            try {
                $this->logger->info('Sending email via Mailjet API');
                $response = $this->mailjetClient->post(Resources::$Email, ['body' => $body]);
                
                if ($response->success()) {
                    $this->logger->info('Email notification sent successfully to doctor: ' . $doctor->getEmail());
                    $this->logger->info('Mailjet response: ' . json_encode($response->getData()));
                } else {
                    $this->logger->error('Failed to send email notification. Response: ' . json_encode($response->getData()));
                }
            } catch (\Exception $e) {
                $this->logger->error('Mailjet API error: ' . $e->getMessage());
                $this->logger->error('Stack trace: ' . $e->getTraceAsString());
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in sendEmailNotification: ' . $e->getMessage());
            $this->logger->error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    #[Route('/search', name: 'app_planning_docteur_search', methods: ['GET'])]
    public function search(Request $request, PlanningDocteurRepository $planningDocteurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            $this->logger->info('Searching for: ' . $query);
            
            // If query is empty, return all records
            $plannings = empty($query) ? 
                $planningDocteurRepository->findAll() : 
                $planningDocteurRepository->search($query);
            
            if (empty($plannings)) {
                return $this->json([]);
            }

            $data = array_map(function($planning) {
                try {
                    return [
                        'idPlanning' => $planning->getIdPlanning(),
                        'docteurNom' => $planning->getDocteur()->getNom(),
                        'dateJour' => $planning->getDateJour()->format('Y-m-d'),
                        'heureDebut' => $planning->getHeureDebut(),
                        'heureFin' => $planning->getHeureFin(),
                        'dossierMedical' => $planning->getDossierMedical() ? [
                            'id' => $planning->getDossierMedical()->getId()
                        ] : null,
                        'csrfToken' => $this->container->get('security.csrf.token_manager')->getToken('delete' . $planning->getIdPlanning())->getValue()
                    ];
                } catch (\Exception $e) {
                    $this->logger->error('Error processing planning: ' . $e->getMessage());
                    return null;
                }
            }, $plannings);

            // Filter out any null values from failed processing
            $data = array_filter($data);

            $this->logger->info('Found ' . count($data) . ' results');
            return $this->json($data);
        } catch (\Exception $e) {
            $this->logger->error('Error in search: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/search/suggestions', name: 'app_planning_docteur_search_suggestions', methods: ['GET'])]
    public function searchSuggestions(Request $request, PlanningDocteurRepository $planningDocteurRepository): JsonResponse
    {
        try {
            $query = $request->query->get('query', '');
            
            if (empty($query)) {
                return $this->json([]);
            }

            $this->logger->info('Getting suggestions for: ' . $query);
            
            $suggestions = $planningDocteurRepository->getSearchSuggestions($query);
            
            if (empty($suggestions)) {
                return $this->json([]);
            }

            $this->logger->info('Found ' . count($suggestions) . ' suggestions');
            return $this->json($suggestions);
        } catch (\Exception $e) {
            $this->logger->error('Error in searchSuggestions: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/', name: 'app_planning_docteur_index', methods: ['GET'])]
    public function index(PlanningDocteurRepository $planningDocteurRepository): Response
    {
        return $this->render('planning_docteur/index.html.twig', [
            'planning_docteurs' => $planningDocteurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_planning_docteur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planningDocteur = new PlanningDocteur();
        $form = $this->createForm(PlanningDocteurType::class, $planningDocteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // First save the planning
                $entityManager->persist($planningDocteur);
                $entityManager->flush();

                // Then try to send notifications
                try {
                    // Send SMS notification
                    $this->sendSmsNotification($planningDocteur);
                } catch (\Exception $e) {
                    $this->logger->error('Error sending SMS notification: ' . $e->getMessage());
                }

                try {
                    // Send email notification
                    $this->sendEmailNotification($planningDocteur);
                } catch (\Exception $e) {
                    $this->logger->error('Error sending email notification: ' . $e->getMessage());
                }

                $this->addFlash('success', 'Le planning a été créé avec succès.');
                return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
            } catch (\Exception $e) {
                $this->logger->error('Error creating planning: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de la création du planning.');
            }
        }

        return $this->render('planning_docteur/new.html.twig', [
            'planning_docteur' => $planningDocteur,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_docteur_show', methods: ['GET'])]
    public function show(PlanningDocteur $planningDocteur): Response
    {
        return $this->render('planning_docteur/show.html.twig', [
            'planning_docteur' => $planningDocteur,
        ]);
    }

    #[Route('/{idPlanning}/edit', name: 'app_planning_docteur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PlanningDocteur $planningDocteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlanningDocteurType::class, $planningDocteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning_docteur/edit.html.twig', [
            'planning_docteur' => $planningDocteur,
            'form' => $form,
        ]);
    }

    #[Route('/{idPlanning}', name: 'app_planning_docteur_delete', methods: ['POST'])]
    public function delete(Request $request, PlanningDocteur $planningDocteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$planningDocteur->getIdPlanning(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($planningDocteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_planning_docteur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/api/{id}/planning', name: 'app_planning_docteur_api_planning', methods: ['GET'])]
    public function getDoctorPlanning(int $id, PlanningDocteurRepository $planningDocteurRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Get planning
            $plannings = $planningDocteurRepository->findBy(['docteur' => $id]);
            $this->logger->info('Found ' . count($plannings) . ' planning entries for doctor ' . $id);
            
            $planningEvents = array_map(function($planning) {
                return [
                    'id' => 'planning_' . $planning->getIdPlanning(),
                    'title' => $planning->getHeureDebut() . ' - ' . $planning->getHeureFin(),
                    'start' => $planning->getDateJour()->format('Y-m-d'),
                    'end' => $planning->getDateJour()->format('Y-m-d'),
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'display' => 'block',
                    'type' => 'planning',
                    'extendedProps' => [
                        'idPlanning' => $planning->getIdPlanning(),
                        'heureDebut' => $planning->getHeureDebut(),
                        'heureFin' => $planning->getHeureFin()
                    ]
                ];
            }, $plannings);

            // Get reservations
            $reservations = $entityManager->getRepository(Reservation::class)->findBy(['docteur' => $id]);
            $this->logger->info('Found ' . count($reservations) . ' reservations for doctor ' . $id);
            
            $reservationEvents = array_map(function($reservation) {
                $event = [
                    'id' => 'reservation_' . $reservation->getIdReservation(),
                    'title' => 'Réservation: ' . $reservation->getHeureDepart(),
                    'start' => $reservation->getDateDebut()->format('Y-m-d'),
                    'end' => $reservation->getDateFin()->format('Y-m-d'),
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                    'display' => 'block',
                    'type' => 'reservation',
                    'extendedProps' => [
                        'idReservation' => $reservation->getIdReservation(),
                        'dateDebut' => $reservation->getDateDebut()->format('Y-m-d'),
                        'dateFin' => $reservation->getDateFin()->format('Y-m-d'),
                        'heureDepart' => $reservation->getHeureDepart()
                    ]
                ];
                $this->logger->info('Reservation event: ' . json_encode($event));
                return $event;
            }, $reservations);

            // Combine both types of events
            $events = array_merge($planningEvents, $reservationEvents);
            $this->logger->info('Total events: ' . count($events));
            $this->logger->info('Events data: ' . json_encode($events));

            return $this->json($events);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching doctor planning and reservations: ' . $e->getMessage());
            $this->logger->error('Stack trace: ' . $e->getTraceAsString());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/api/{id}/planning/raw', name: 'app_planning_docteur_api_planning_raw', methods: ['GET'])]
    public function getDoctorPlanningRaw(int $id, PlanningDocteurRepository $planningDocteurRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        try {
            // Get planning
            $plannings = $planningDocteurRepository->findBy(['docteur' => $id]);
            $this->logger->info('Found ' . count($plannings) . ' planning entries for doctor ' . $id);
            
            $planningEvents = array_map(function($planning) {
                return [
                    'id' => 'planning_' . $planning->getIdPlanning(),
                    'title' => $planning->getHeureDebut() . ' - ' . $planning->getHeureFin(),
                    'start' => $planning->getDateJour()->format('Y-m-d'),
                    'end' => $planning->getDateJour()->format('Y-m-d'),
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'display' => 'block',
                    'type' => 'planning',
                    'extendedProps' => [
                        'idPlanning' => $planning->getIdPlanning(),
                        'heureDebut' => $planning->getHeureDebut(),
                        'heureFin' => $planning->getHeureFin()
                    ]
                ];
            }, $plannings);

            // Get reservations
            $reservations = $entityManager->getRepository(Reservation::class)->findBy(['docteur' => $id]);
            $this->logger->info('Found ' . count($reservations) . ' reservations for doctor ' . $id);
            
            $reservationEvents = array_map(function($reservation) {
                return [
                    'id' => 'reservation_' . $reservation->getIdReservation(),
                    'title' => 'Réservation',
                    'start' => $reservation->getDateDebut()->format('Y-m-d'),
                    'end' => $reservation->getDateFin()->format('Y-m-d'),
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745',
                    'display' => 'block',
                    'type' => 'reservation',
                    'extendedProps' => [
                        'idReservation' => $reservation->getIdReservation(),
                        'dateDebut' => $reservation->getDateDebut()->format('Y-m-d'),
                        'dateFin' => $reservation->getDateFin()->format('Y-m-d')
                    ]
                ];
            }, $reservations);

            // Combine both types of events
            $events = array_merge($planningEvents, $reservationEvents);
            $this->logger->info('Total events: ' . count($events));
            $this->logger->info('Events data: ' . json_encode($events));

            return $this->json($events);
        } catch (\Exception $e) {
            $this->logger->error('Error fetching doctor planning and reservations: ' . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
