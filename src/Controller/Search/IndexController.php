<?php

namespace App\Controller\Search;

use App\Request\Search\IndexRequestHandler;
use App\Request\Search\IndexRequest;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class IndexController extends AbstractController
{
    #[Route('/search', name: 'book_index', methods: ['GET'])]
    #[ParamConverter('searchIndex', class: 'App\Request\Search\IndexRequest')]
    public function index(IndexRequest $request, IndexRequestHandler $handler, EntityManagerInterface $entityManager): JsonResponse
    {
        return $this->json($handler($request));
    }
}