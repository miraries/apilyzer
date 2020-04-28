<?php

namespace Miraries\Apilyzer;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Miraries\Apilyzer\Skeleton\SkeletonClass
 */
class ApilyzerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'apilyzer';
    }
}
