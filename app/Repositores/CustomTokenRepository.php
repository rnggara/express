<?php

namespace App\Repositories;

use Laravel\Passport\TokenRepository;
use Laravel\Passport\Token;

class CustomTokenRepository extends TokenRepository
{
    /**
     * Overrides the method to create a new token.
     */
    public function create($attributes)
    {
        // Access session data
        $sessionValue = session('state'); // Replace 'key_name' with your actual session key

        // Set the default value for a column from session data
        $attributes['column_name'] = $sessionValue ?? null; // Replace 'column_name' with your actual column name

        // Create the token with modified attributes
        $token = new Token($attributes);
        $token->save();

        return $token;
    }
}