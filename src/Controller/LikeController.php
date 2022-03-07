<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Like;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'like')]
    public function like(Film $film, EntityManagerInterface $manager, LikeRepository $repository): Response
    {
        $like = $repository->findOneBy([
            'author' => $this->getUser(),
            'film' => $film
        ]);


        if ($like) {
            $manager->remove($like);
            $liked = false;
        } else {
            $like = new Like();
            $like->setAuthor($this->getUser());
            $like->setFilm($film);
            $manager->persist($like);
            $liked = true;
        }
        $manager->flush();

        return $this->json([
            'count' => $repository->count(['film' => $film]),
            'liked' => $liked,
        ], 200);
    }
}
