<?php

namespace App\Services;

use App\Interfaces\CustomerInterface;
use Illuminate\Http\Request;

class CustomerService implements CustomerInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function get(Request $request): array
    {
        $id = $request->id ?? 0;
        $dbHandlers = app(DatabaseHandlerService::class);

        if($id){
            $res = $dbHandlers->find($id);
        }else{
            $res = $dbHandlers->all();
        }

        return $res;
    }
}
