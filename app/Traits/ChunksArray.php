<?php

namespace App\Traits;

trait ChunksArray
{
    /**
     * Chunk an array and process each chunk with a callback.
     *
     * @param array $data
     * @param int $size
     * @param \Closure $callback
     * @return void
     */
    public function chunkArray(array $data, int $size, \Closure $callback): void
    {
        foreach (array_chunk($data, $size) as $chunk) {
            $callback($chunk);
        }
    }
}
