<?php declare(strict_types=1);

namespace App\Infrastructure\ParamConverter;

use App\Application\UseCases\TodoList\DeleteListItemCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class DeleteTodoListItemCommandParamConverter implements ParamConverterInterface
{

    public function apply(Request $request, ParamConverter $configuration)
    {
        $command = new DeleteListItemCommand($request->get('listId'), $request->get('listItemId'));
        $request->attributes->set($configuration->getName(), $command);

        return true;
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass() !== DeleteListItemCommand::class) {
            return false;
        }

        return true;
    }
}
