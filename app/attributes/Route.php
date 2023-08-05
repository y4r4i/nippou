<?php

declare(strict_types=1);

#[Attribute(Attribute::TARGET_METHOD)]
class Route
{


    public function __construct(public string $pattern, public array $methods=['GET'])
    {
        $this->methods = array_map(
            function (string $str) {
                return strtoupper($str);
            },
            $this->methods
        );

    }//end __construct()


}//end class
