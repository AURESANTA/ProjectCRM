<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Form\TagType;
use App\Entity\Tag;
use App\Repository\TagRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * Class TagController
 * @package App\Controller
 * @Route("/tag",name="tag_")
 */
class TagController extends AbstractController
{

    private $tagRepository;
    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager, TagRepository $tagRepository)
    {
    $this->tagRepository = $tagRepository;
    $this->entityManager = $entityManager;

    }
    /**
     * @Route("/list", name="list")
     */
    public function index()
    {
        $tags = $this->tagRepository->findAll();
        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

/**
     * @Route("/new",name="new")
     */
    public function newContact (Request $request)

    {

        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->addFlash('success', "La catégorie a été ajoutée !");

            return $this->redirectToRoute('tag_list');
        }

        return $this->render('tag/form.html.twig', [
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