<?php
 
namespace App\Repositories;

use App\Models\User;

class UserRepository {
    public function findByIdentifier($identifier) {
        return User::where('email', $identifier)->orWhere('phone', $identifier)->first();
    }
}
