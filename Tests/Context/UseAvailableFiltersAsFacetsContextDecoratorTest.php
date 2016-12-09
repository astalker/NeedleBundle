<?php

namespace Markup\NeedleBundle\Tests\Context;

use Markup\NeedleBundle\Context\UseAvailableFiltersAsFacetsContextDecorator;

/**
* A test for a search context decorator that makes the context use the available filter names for facets.
*/
class UseAvailableFiltersAsFacetsContextDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->context = $this->createMock('Markup\NeedleBundle\Context\SearchContextInterface');
        $this->facetProvider = $this->createMock('Markup\NeedleBundle\Facet\FacetProviderInterface');
        $this->decorator = new UseAvailableFiltersAsFacetsContextDecorator($this->context, $this->facetProvider);
    }

    public function testIsSearchContext()
    {
        $this->assertTrue($this->decorator instanceof \Markup\NeedleBundle\Context\SearchContextInterface);
    }

    public function testGetAvailableFilterNames()
    {
        $filterNames = ['filter1', 'filter2', 'filter3'];
        $this->context
            ->expects($this->any())
            ->method('getAvailableFilterNames')
            ->will($this->returnValue($filterNames));
        $this->assertEquals($filterNames, $this->decorator->getAvailableFilterNames());
    }

    public function testGetFacets()
    {
        $facet = $this->createMock('Markup\NeedleBundle\Attribute\AttributeInterface');
        $this->facetProvider
            ->expects($this->any())
            ->method('getFacetByName')
            ->will($this->returnValue($facet));
        $filterNames = ['filter1', 'filter2', 'filter3'];
        $this->context
            ->expects($this->any())
            ->method('getAvailableFilterNames')
            ->will($this->returnValue($filterNames));
        $facets = $this->decorator->getFacets();
        $this->assertCount(3, $facets);
        $this->assertContainsOnly('Markup\NeedleBundle\Attribute\AttributeInterface', $facets);
    }

    //other methods simply delegate down, so skipping unit tests as little chance of regression
}
