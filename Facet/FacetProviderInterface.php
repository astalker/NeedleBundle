<?php

namespace Markup\NeedleBundle\Facet;

/**
 * An interface for a provider object that can provide facet objects.
 *
 * @deprecated
 **/
interface FacetProviderInterface
{
    /**
     * Gets a facet object using a name.  Returns null if name does not correspond to known facet.
     *
     * @param  string                                             $name
     * @return \Markup\NeedleBundle\Attribute\AttributeInterface|null
     **/
    public function getFacetByName($name);
}
