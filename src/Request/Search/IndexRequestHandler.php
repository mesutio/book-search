<?php

namespace App\Request\Search;

use App\Repository\BookRepository;
use App\Response\Search\IndexResponse;

class IndexRequestHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(IndexRequest $indexRequest): array
    {
        $response = $this->bookRepository->search($indexRequest->getFilterParams());

        return IndexResponse::create($response);
    }
}
