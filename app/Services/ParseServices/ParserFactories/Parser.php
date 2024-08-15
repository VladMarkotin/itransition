<?php

namespace App\Services\ParseServices\ParserFactories;

interface Parser
{
    public function createParser(): Parser;
}
