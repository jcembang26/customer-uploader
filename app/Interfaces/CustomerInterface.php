<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CustomerInterface
{
    public function get(Request $request): array;
}
