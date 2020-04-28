<?php

namespace Miraries\Apilyzer\Tests;

use Orchestra\Testbench\TestCase;
use Miraries\Apilyzer\ApilyzerServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [ApilyzerServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
