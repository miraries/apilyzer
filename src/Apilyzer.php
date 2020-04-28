<?php

namespace Miraries\Apilyzer;

use Illuminate\Support\Facades\Route;
use PhpParser\{Error, Node, NodeFinder, ParserFactory};
use PhpParser\PrettyPrinter\Standard as PrettyPrinter;

class Apilyzer
{
    public static function handleRequest()
    {
        [$controller, $action] = self::getController();

        try {
            $res = self::parseController($controller, $action);
        } catch (Error $e) {
            throw new \DomainException('Could not parse ' . $controller . '. ' . $e);
        }

        dump($res);

//        dump($controllerPath, $action);
    }

    private static function getController(): array
    {
        $router = Route::current();

        $controller = get_class($router->getController());
//        $controllerPath = app_path($controller . '.php');
        $action = $router->getActionMethod();
        $controllerPath = base_path($controller . '.php');

        if (!file_exists($controllerPath))
            throw new \DomainException($controllerPath . ' not found.');

        return [$controllerPath, $action];
    }

    /**
     * @param string $file
     * @param string $method
     * @return mixed
     */
public static function parseController(string $file, string $method)
    {
        $code = file_get_contents($file);

        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);
        $nodeFinder = new NodeFinder;
        $prettyPrinter = new PrettyPrinter;

        $ast = $parser->parse($code);

        $methodNode = $nodeFinder->find($ast, static function (Node $node) use ($method) {
            return $node instanceof Node\Stmt\ClassMethod
                && $node->name->name === $method;
        });

        if (!$methodNode)
            throw new \DomainException('Could not find method ' . $method);

        $validateExpression = $nodeFinder->find($methodNode, static function (Node $node) {
            return $node instanceof Node\Stmt\Expression
                && ($node->expr->name->name ?? null) === 'validate';
        });

        if (!$validateExpression)
            throw new \DomainException('Could not find `validate` experssion in method ' . $method);

        $validateExpressionArrayArg = $nodeFinder->find($validateExpression[0], static function (Node $node) {
            return $node instanceof Node\Arg
                && $node->value->getType() === 'Expr_Array';
        });

        if (!$validateExpressionArrayArg)
            throw new \DomainException('Could not find array (rules) parameter in `validate` function in method ' . $method);

        $validateArrayString = $prettyPrinter->prettyPrint($validateExpressionArrayArg);

        // TODO: Use the parser to traverse array, filter out custom rules
        $rules = null;
        eval('$rules = ' . $validateArrayString . ';');

        return $rules;
    }
}
