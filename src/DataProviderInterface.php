<?php
declare(strict_types=1);

namespace Api;

interface DataProviderInterface
{
    public function get(string $path): string;
}