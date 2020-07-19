<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Form\ContactType;
use App\Entity\Contact;
use App\Entity\Tag;
use App\Repository\ContactRepository;
use App\Repository\TagRepository;
use PHPUnit\Framework\TestCase;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Class ContactController
 * @package App\Controller
 * @Route("/contact",name="contact_")
 */
class ContactController extends AbstractController
{

    private $groupRepository;
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager, ContactRepository $contactRepository)
    {
    $this->contactRepository = $contactRepository;
    $this->entityManager = $entityManager;

    }
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        $contacts = $this->contactRepository->findAll();
        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

/**
     * @Route("/new",name="new")
     */
    public function newContact (Request $request)

    {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash('success', "Le contact a été ajouté !");

            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/update/{id}", name="update")
     */

    public function updateContact (Contact $contact, Request $request)

    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash('success', "Le contact a bien été modifié !");

            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */

    public function deleteContact (Contact $contact, Request $request)
    {
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
        $this->addFlash('success', "Le contact a bien été supprimé !");
        return $this->redirectToRoute('contact_list');
    }

}

