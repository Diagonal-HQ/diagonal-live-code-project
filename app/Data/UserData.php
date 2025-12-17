<?php

namespace App\Data;

use Spatie\LaravelData\Data;

/**
 * Class UserData
 *
 * Data transfer object for user information.
 */
class UserData extends Data
{
    /**
     * Create a new user data instance.
     *
     * @param  string  $id  The user's unique identifier
     * @param  string  $name  The user's name
     * @param  string  $email  The user's email address
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
    ) {}
}
