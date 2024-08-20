<?php

declare(strict_types=1);

namespace App\Services\ParseServices\ParserFactories;

use App\Services\HandleDataServices\HandleDataService;
use App\Services\ParseServices\ParserFactories\Parser;

class CsvParserFactory implements Parser
{
    private $handleDataService;

    public function createParser(): Parser
    {
        // TODO: Implement createParser() method.
    }

    public function __construct(HandleDataService $handleDataService)
    {
        $this->handleDataService = $handleDataService;
    }

    public function parse(string $data): void
    {
        echo "Parsing CSV data...";
    }
}
