<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Server\Models\User;

use Atlas\Table\Row;

/**
 * @property mixed $row_id int(10,0) NOT NULL
 * @property mixed $inserted_at datetime NOT NULL
 * @property mixed $updated_at datetime NOT NULL
 * @property mixed $email varchar(255)
 * @property mixed $birth_day date
 * @property mixed $birth_place varchar(255)
 * @property mixed $first_name varchar(255)
 * @property mixed $last_name varchar(255)
 * @property mixed $hashed_password varchar(255) NOT NULL
 * @property mixed $rgpd bit(1) NOT NULL
 * @property mixed $newsletter bit(1) NOT NULL
 */
class UserRow extends Row
{
    protected $cols = [
        'row_id' => null,
        'inserted_at' => null,
        'updated_at' => null,
        'email' => null,
        'birth_day' => null,
        'birth_place' => null,
        'first_name' => null,
        'last_name' => null,
        'hashed_password' => null,
        'rgpd' => null,
        'newsletter' => null,
    ];
}
