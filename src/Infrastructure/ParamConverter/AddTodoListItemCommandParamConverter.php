<?php declare(strict_types=1);

namespace App\Infrastructure\ParamConverter;

use App\Application\UseCases\TodoList\AddListItemCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class AddTodoListItemCommandParamConverter implements ParamConverterInterface
{

    public function apply(Request $request, ParamConverter $configuration)
    {
        $requestParams = json_decode($request->getContent(), true);
        $command = new AddListItemCommand(
            $request->get('listId'),
            $requestParams['title'] ?? '',
            $requestParams['completed'] ?? false
        );
        $request->attributes->set($configuration->getName(), $command);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass() !== AddListItemCommand::class) {
            return false;
        }

        return true;
    }
}
