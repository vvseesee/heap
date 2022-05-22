<?php

namespace Vvseesee\Heap;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

class Heap
{
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = collect($config->get('heap'));
    }

    public function run()
    {
        echo __METHOD__;
    }
}
