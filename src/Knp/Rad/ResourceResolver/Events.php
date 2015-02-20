<?php

namespace Knp\Rad\ResourceResolver;

final class Events
{
    /**
     * Dispatched before a resource resolution, you can set your own resource
     */
    const BEFORE_RESOURCE_RESOLVED = 'knp_rad_resource_resolver.before_resource_resolved';
    /**
     * Dispatched after a resource resolution
     */
    const RESOURCE_RESOLVED = 'knp_rad_resource_resolver.resource_resolved';

    /**
     * Dispatched when a resources is added to the resource container
     */
    const RESOURCES_ADDED = 'knp_rad_resource_resolver.resource.added';
    /**
     * Dispatched when a resources is removed from the resource container
     */
    const RESOURCES_REMOVED = 'knp_rad_resource_resolver.resource.removed';
}
