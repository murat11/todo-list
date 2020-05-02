<?php declare(strict_types=1);

namespace App\Infrastructure\ParamConverter;

use App\Application\UseCases\TodoList\CreateListCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateTodoListCommandParamConverter implements ParamConverterInterface
{

    public function apply(Request $request, ParamConverter $configuration)
    {
        $requestParams = json_decode($request->getContent(), true);
        $command = new CreateListCommand(
            $requestParams['name'] ?? '',
                $requestParams['participants'] ?? []
        );
        $request->attributes->set($configuration->getName(), $command);
    }

    public function supports(ParamConverter $configuration)
    {
        if ($configuration->getClass() !== CreateListCommand::class) {
            return false;
        }

        return true;
    }
}
