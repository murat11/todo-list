<?php declare(strict_types=1);

namespace App\Infrastructure\ParamConverter;

use App\Application\UseCases\TodoList\AddListItemCommand;
use App\Application\UseCases\TodoList\UpdateListItemCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class UpdateTodoListItemCommandParamConverter implements ParamConverterInterface
{

    public function apply(Request $request, ParamConverter $configuration)
    {
        $requestParams = json_decode($request->getContent(), true);
        $command = new UpdateListItemCommand(
            $request->get('listId'),
            $request->get('listItemId'),
            $requestParams['title'] ?? '',
            $requestParams['completed'] ?? false
        );
        $request->attributes->set($configuration->getName(), $command);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass() !== UpdateListItemCommand::class) {
            return false;
        }

        return true;
    }
}
