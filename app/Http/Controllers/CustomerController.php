<?php

namespace App\Http\Controllers;

use App\Interfaces\CustomerInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function list(CustomerInterface $customerInterface, Request $request): object
    {
        return response()->json($customerInterface->get($request));
    }

    public function show(CustomerInterface $customerInterface, Request $request): object
    {
        return response()->json($customerInterface->get($request));
    }
}
