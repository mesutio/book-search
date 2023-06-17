<?php

namespace App\Request\Search;

class IndexHandler
{
    public function __invoke(IndexRequest $indexRequest)
    {
        return json_encode([]);
    }
}