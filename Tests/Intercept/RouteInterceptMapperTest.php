<?php

namespace Markup\NeedleBundle\Tests\Intercept;

use Markup\NeedleBundle\Intercept\RouteInterceptMapper;

/**
* A test for an intercept mapper for the route definition type.
*/
class RouteInterceptMapperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->urlGenerator = $this->createMock('Symfony\Component\Routing\Generator\UrlGeneratorInterface');
        $this->mapper = new RouteInterceptMapper($this->urlGenerator);
    }

    public function testIsInterceptMapper()
    {
        $this->assertInstanceOf('Markup\NeedleBundle\Intercept\TypedInterceptMapperInterface', $this->mapper);
    }

    public function testIsRouteType()
    {
        $this->assertEquals('route', $this->mapper->getType());
    }

    public function testMapDefinitionToIntercept()
    {
        $route = 'route';
        $routeParams = ['param' => 'yes'];
        $properties = ['route' => $route, 'params' => $routeParams];
        $definition = $this->createMock('Markup\NeedleBundle\Intercept\DefinitionInterface');
        $definition
            ->expects($this->any())
            ->method('getProperties')
            ->will($this->returnValue($properties));
        $uri = 'i am the uri';
        $this->urlGenerator
            ->expects($this->any())
            ->method('generate')
            ->with($this->equalTo($route), $this->equalTo($routeParams), $this->equalTo(true))
            ->will($this->returnValue($uri));
        $intercept = $this->mapper->mapDefinitionToIntercept($definition);
        $this->assertInstanceOf('Markup\NeedleBundle\Intercept\InterceptInterface', $intercept);
        $this->assertEquals($uri, $intercept->getUri());
    }

    public function testRoutelessDefinitionThrowsException()
    {
        $this->setExpectedException('Markup\NeedleBundle\Intercept\UnresolvedInterceptException');
        $definition = $this->createMock('Markup\NeedleBundle\Intercept\DefinitionInterface');
        $properties = [];
        $definition
            ->expects($this->any())
            ->method('getProperties')
            ->will($this->returnValue($properties));
        $this->mapper->mapDefinitionToIntercept($definition);
    }

    public function testRouteNotFoundExceptionThrowsUnresolvedException()
    {
        $this->setExpectedException('Markup\NeedleBundle\Intercept\UnresolvedInterceptException');
        $route = 'route';
        $routeParams = ['param' => 'yes'];
        $properties = ['route' => $route, 'params' => $routeParams];
        $definition = $this->createMock('Markup\NeedleBundle\Intercept\DefinitionInterface');
        $definition
            ->expects($this->any())
            ->method('getProperties')
            ->will($this->returnValue($properties));
        $this->urlGenerator
            ->expects($this->any())
            ->method('generate')
            ->will($this->throwException(new \Symfony\Component\Routing\Exception\RouteNotFoundException()));
        $this->mapper->mapDefinitionToIntercept($definition);
    }
}
