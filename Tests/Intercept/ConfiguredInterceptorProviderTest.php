<?php

namespace Markup\NeedleBundle\Tests\Intercept;

use Markup\NeedleBundle\Intercept\ConfiguredInterceptorProvider;
use Mockery as m;

class ConfiguredInterceptorProviderTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->interceptor = m::mock('Markup\NeedleBundle\Intercept\Interceptor');
        $closure = function () {
            return $this->interceptor;
        };
        $this->configurator = m::mock('Markup\NeedleBundle\Intercept\InterceptorConfiguratorInterface');
        $this->provider = new ConfiguredInterceptorProvider($closure, $this->configurator);
    }

    protected function tearDown()
    {
        m::close();
    }

    public function testCreateInterceptor()
    {
        $config = [
            'sale' => [
                'terms' => ['sale'],
                'type' => 'route',
                'route' => 'sale',
            ],
        ];
        $this->configurator
            ->shouldReceive('configureInterceptor')
            ->with($this->interceptor, $config)
            ->once();
        $this->assertSame($this->interceptor, $this->provider->createInterceptor($config));
    }
}
