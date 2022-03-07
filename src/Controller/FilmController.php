<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Film;
use App\Form\CommentType;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/film', name: 'film_index')]
    public function index(FilmRepository $repo): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $repo->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'film_show')]
    public function show(Film $film, Request $request): Response
    {

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        return $this->renderForm('film/show.html.twig', [
            'film' => $film,
            'form_comment' => $form
        ]);
    }

    #[Route('/new', name: 'film_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {

        $film = new Film;
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $film->setAuthor($this->getUser());
            $em->persist($film);
            $em->flush();
            return $this->redirectToRoute('film_index');
        }

        return $this->renderForm('film/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'film_delete')]
    public function delete(Film $film,  EntityManagerInterface $em): Response
    {
        $em->remove($film);
        $em->flush();
        return $this->redirectToRoute('film_index');
    }

    #[Route('/update/{id}', name: 'film_update')]
    public function update(Request $request,  Film $film,  EntityManagerInterface $em): Response
    {

        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($film);
            $em->flush();
            return $this->redirectToRoute('film_show', ['id' => $film->getId()]);
        }

        return $this->renderForm('film/new.html.twig', [
            'form' => $form
        ]);
    }
}
