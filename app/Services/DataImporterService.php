<?php

namespace App\Services;

use App\Interfaces\DataImporterInterface;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DataImporterService implements DataImporterInterface
{
    protected $rules = [];
    
    public function __construct() {
        $this->rules = [
            'requiredString' => 'required|string'
        ];
    }

    public function import(array $params = []): array
    {
        
        $url = env('CUSTOMER_PROVIDER_URL', 'https://randomuser.me/api');
        
        if(empty($url)){
            return [];
        }
        
        $data = Http::get($url, [
            'results' => $params['limit'],
            'nat' => $params['nat']
        ]);
        
        if(empty($data)){
            return [];
        }

        // validate data
        $payload = $this->validateData($data->json());

        $invalidData = array_filter($payload, function($item){
            return !$item['is_valid'];
        });

        $validData = array_filter($payload, function($item){
            return $item['is_valid'];
        });
        
        $dbHandler = app(DatabaseHandlerService::class);
        $processImport = $dbHandler->upsert($validData, $params);

        return $this->gatherResults($processImport, $params, $invalidData);
    }

    private function validateData(array $arr = []): array
    {
        $res = [];
        $data = $arr['results'] ?? [];

        if(empty($data)){
            return $res;
        }

        foreach ($data as $value) {
            $isValid = Validator::make($value, [
                'name' => 'required|array',
                'name.first' => $this->rules['requiredString'],
                'name.last' => $this->rules['requiredString'],
                'login' => 'required|array',
                'login.username' => $this->rules['requiredString'],
                'login.password' => $this->rules['requiredString'],
                'email' => 'required|email',
                'gender' => $this->rules['requiredString'],
                'location' => 'array',
                'location.country' => 'string',
                'location.city' => 'string',
                'phone' => 'string'
            ]);

            $res[] = $this->prepareData($value, $isValid);
        }
        
        return $res;
    }

    private function gatherResults(array $data = [], array $params = [], array $invalidData = []): array
    {
        if(!empty($invalidData)){
            $data['failed'] = (int) $data['failed'] + count($invalidData);
        }

        if($params['error']){
            $data['failures'] = array_merge($this->displayValidationError($invalidData), $this->displayDBError($data['failures']));
        }else{
            unset($data['failures']);
        }

        return $data;
    }

    private function displayValidationError(array $data = []): array
    {
        if(empty($data)){
            return [];
        }

        $res = [];

        foreach ($data as $value) {
            $res[$value['email']] = 'Validation: '. implode(', ', $value['error_msg']);
        }

        return $res;
    }

    private function displayDBError(array $data = []): array
    {
        if(empty($data)){
            return [];
        }

        $res = [];

        foreach ($data as $value) {
            $res[$value['row']['email']] = 'DB: '. $value['message'];
        }

        return $res;
    }

    private function prepareData(array $data = [], object $isValid = null): array
    {
        if(empty($data) || !$isValid){
            return [];
        }

        return [
            'first_name' => isset($data['name']) ? $data['name']['first'] : '',
            'last_name' => isset($data['name']) ? $data['name']['last'] : '',
            'email' => $data['email'],
            'username' => isset($data['login']) ? $data['login']['username'] : '',
            'password' => md5(isset($data['login']) ? $data['login']['password'] : ''),
            'gender' => $data['gender'],
            'country' => isset($data['location']) ? $data['location']['country'] : '',
            'city' => isset($data['location']) ? $data['location']['city'] : '',
            'phone' => $data['phone'],
            'is_valid' => !$isValid->fails(),
            'error_msg' => $isValid->fails() ? $isValid->errors()->all() : []
        ];
    }
}
