<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'first_name', 'last_name', 'user_name', 'email', 'password', 'gender', 'country', 'city', 'phone', 'created_at', 'updated_by'];
}
