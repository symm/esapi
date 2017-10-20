<?php
/**
 * Created by PhpStorm.
 * User: skambalin
 * Date: 19.10.17
 * Time: 11.56
 */

namespace Everywhere\Api\Schema;

use Everywhere\Api\Contract\Schema\ResolverInterface;
use Everywhere\Api\Contract\Schema\TypeConfigDecoratorInterface;

class TypeDecorator implements TypeConfigDecoratorInterface
{
    protected $resolversMap;

    /**
     * @var callable
     */
    protected $getResolver;

    public function __construct(array $resolversMap, callable $getResolver)
    {
        $this->resolversMap = $resolversMap;
        $this->getResolver = $getResolver;
    }

    public function decorate(array $typeConfig)
    {
        $name = $typeConfig["name"];

        if (empty($this->resolversMap[$name])) {
            return $typeConfig;
        }

        /**
         * @var $resolver ResolverInterface
         */
        $resolver = call_user_func($this->getResolver, $this->resolversMap[$name]);

        if (!$resolver) {
            return $typeConfig;
        }

        $typeConfig["resolveField"] = function($root, $args, $context, $info) use($resolver) {
            return $resolver->resolve($root, $args, $context, $info);
        };

        return $typeConfig;
    }
}