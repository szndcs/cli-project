<?php

namespace App\Controller;

use App\Entity\InstagramSources;
use Doctrine\ORM\EntityManagerInterface;

class InstagramController
{

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function addInstagramEntity(): string
    {
        $post = new InstagramSources();
        $post->setName('Manolo');

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return 'Saved new Instagram entity with id '.$post->getId();
    }

    public function findIt($id = null, $desiredSource = null, $numOfPosts = null): array
    {
        $answer = [];
        $instagramRepository = $this->entityManager
                                    ->getRepository(InstagramSources::class);
        if (!is_null($id) and is_null($desiredSource) and is_null($numOfPosts)) {
            $answer = $instagramRepository->findIt($id);

        } else if (!is_null($id) and !is_null($desiredSource) and is_null($numOfPosts)) {
            $answer = $instagramRepository->findItOnlyIn($id, $desiredSource);

        } else if (!is_null($id) and !is_null($numOfPosts) and !is_null($desiredSource)) {
            $answer = $instagramRepository->findItOnlyInWithPosts($id, $desiredSource, $numOfPosts);

        } else if (!is_null($id) and !is_null($numOfPosts) and is_null($desiredSource)) {
            $answer = $instagramRepository->findItOnlyWithPosts($id, $numOfPosts);
        }
        return $answer;
    }
}