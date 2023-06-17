<?php

namespace App\Request\Search;

use App\Component\Request\FilterParams;
use App\Request\AbstractFilterConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class IndexRequestConverter extends AbstractFilterConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $searchIndexRequest = new IndexRequest(
            new FilterParams($this->parseFilters($request))
        );

        $request->attributes->set($configuration->getName(), $searchIndexRequest);

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return IndexRequest::class === $configuration->getClass();
    }

    protected function getAllowedFilters(): array
    {
        return IndexRequest::getAllowedFilters();
    }
}