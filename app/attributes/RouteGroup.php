<?php

declare(strict_types=1);

#[Attribute(Attribute::TARGET_CLASS)]
class RouteGroup
{


    public function __construct(public string $pattern)
    {

    }//end __construct()


}//end class
