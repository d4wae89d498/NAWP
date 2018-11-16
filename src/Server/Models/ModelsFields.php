<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 14:56
 */

namespace App\Server\Models;

use App\Server\Models\User\User;
use App\Server\Models\User\UserTable;

class ModelsFields
{
    public static function getModelsFields(): array
    {
        return [
            User::class => [
                UserTable::COLUMNS["row_id"]["name"]            => "",
                UserTable::COLUMNS["inserted_at"]["name"]       => "",
                UserTable::COLUMNS["updated_at"]["name"]        => "",
                UserTable::COLUMNS["email"]["name"]             => "",
                UserTable::COLUMNS["first_name"]["name"]        => "",
                UserTable::COLUMNS["last_name"]["name"]         => "",
                UserTable::COLUMNS["hashed_password"]["name"]   => "",
                UserTable::COLUMNS["rgpd"]["name"]              => "",
                UserTable::COLUMNS["newsletter"]["name"]        => "",
            //    UserTable::COLUMNS["role"]["name"]              => "", SHOULD WORK BY DEFAULT

            ]
        ];
    }
}
