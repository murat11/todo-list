<?php declare(strict_types=1);

namespace App\Infrastructure\ParamConverter;

use App\Application\UseCases\TodoList\GetListItemsQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class GetTodoListItemsQueryParamConverter implements ParamConverterInterface
{

    public function apply(Request $request, ParamConverter $configuration)
    {
        $request->attributes->set($configuration->getName(), new GetListItemsQuery($request->get('listId')));
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass() !== GetListItemsQuery::class) {
            return false;
        }

        return true;
    }
}
