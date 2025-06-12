<?php

namespace App\Interfaces;

interface DatabaseHandlerInterface
{
    public function upsert(array $data): array;
    public function all(): array;
    public function find(int $id): ?array;
}
