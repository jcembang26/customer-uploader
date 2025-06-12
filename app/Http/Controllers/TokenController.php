<?php

namespace App\Http\Controllers;

use Doctrine\DBAL\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller
{
    public function __construct(
        private Connection $db
    ) {}

    public function createToken(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $name = $request->input('name');

        // Check if user exists
        $user = $this->db->fetchAssociative('SELECT * FROM users WHERE email = ?', [$email]);

        if (!$user) {
            // Create new user (password is required by JWT)
            $password = Hash::make('default-password');

            $this->db->insert('users', [
                'email' => $email,
                'name' => $name,
                'password' => $password,
            ]);

            $user = $this->db->fetchAssociative('SELECT * FROM users WHERE email = ?', [$email]);
        }

        // Create token payload manually
        $customClaims = [
            'sub' => (string) $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
        ];
        
        // Automatically adds iat, exp, jti, etc.
        $payload = JWTFactory::customClaims($customClaims)->make();
        $token = JWTAuth::encode($payload)->get();

        return response()->json([
            'message' => 'Token created successfully.',
            'token' => $token
        ]);
    }
}
