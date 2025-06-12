<?php

namespace App\Interfaces;

interface DataImporterInterface
{
    public function import(array $params = []): array;
}
