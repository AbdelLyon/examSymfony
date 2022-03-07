<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Film;
use App\Form\CommentType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    #[Route('comment/new/{id}', name: 'comment_new')]
    public function new(Request $request, Film $film, EntityManagerInterface $em): Response
    {

        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setfilm($film);
            $comment->setCreatedAt(new DateTimeImmutable());
            $comment->setAuthor($this->getUser());

            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('film_show', ['id' => $comment->getfilm()->getId()]);
        }

        return $this->renderForm('comment/_form.html.twig', [
            'form_comment' => $form
        ]);
    }

    #[Route('comment/delete/{id}', name: 'comment_delete')]
    public function delete(Comment $comment,  EntityManagerInterface $em): Response
    {
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('film_show', ['id' => $comment->getFilm()->getId()]);
    }

    #[Route('comment/update/{id}', name: 'comment_update')]
    public function update(Request $request,  Comment $comment,  EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('film_show', ['id' => $comment->getfilm()->getId()]);
        }

        return $this->renderForm('film/show.html.twig', [
            'form_comment' => $form,
            'film' => $comment->getFilm()
        ]);
    }
}
