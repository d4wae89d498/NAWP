<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 14:56
 */

namespace App\Server\Models;

use App\Ipolitic\Nawpcore\Fields\DateField;
use App\Ipolitic\Nawpcore\Fields\EmailField;
use App\Ipolitic\Nawpcore\Fields\PinField;
use App\Ipolitic\Nawpcore\Fields\PlaceField;
use App\Ipolitic\Nawpcore\Fields\SelectMultipleField;
use App\Ipolitic\Nawpcore\Fields\TextField;
use App\Ipolitic\Nawpcore\Fields\ToggleField;
use App\Server\Models\User\UserRecord;
use App\Server\Models\User\UserTable;

class ModelsFields
{
    public static function getModelsFields(): array
    {
        if (!defined("n")) {
            define("n", "name");
        }
        $output = [];
        /**
         *  USER FIELDS
         **/
        $minAge = 18;
        $minFirstNameLength = $minLastNameLength = 3;
        $maxFirstNameLength = $maxLastNameLength = 255;
        $minPasswordLength = 4;
        $maxPasswordLength = 6;
        $output[UserRecord::class] = [
            UserTable::COLUMNS["email"][n]             => [EmailField::class, [
                "icon"          => "fa fa-italic",
                "placeHolder"   => "Email"
            ]],
            UserTable::COLUMNS["birth_day"][n]         => [DateField::class, [
                "icon"          => "fa fa-envelope-o",
                "range"         => [-2208988800, 2208988800 + $minAge * 365 * 24 * 60 * 60]
            ]],
            UserTable::COLUMNS["birth_place"][n]       => [PlaceField::class, [
                "icon"          => "fa fa-location-arrow",
                "placeHolder"   => "Paris 9, FRANCE",
            ]],
            UserTable::COLUMNS["first_name"][n]        => [TextField::class, [
                "icon"          => "fa fa-italic",
                "placeHolder"   => "John",
                "length"        => [$minFirstNameLength, $maxFirstNameLength],
            ]],
            UserTable::COLUMNS["last_name"][n]         => [TextField::class, [
                "icon"          => "fa fa-font",
                "placeHolder"   => "Doe",
                "length"        => [$minLastNameLength, $maxLastNameLength],
            ]],
            UserTable::COLUMNS["hashed_password"][n]   => [PinField::class, [
                "icon"          => "fa fa-key",
                "length"        => [$minPasswordLength, $maxPasswordLength],
                "usePwdHash"    => true
            ]],
            UserTable::COLUMNS["rgpd"][n]              => [ToggleField::class, [
                "description"   => "I accept the use of my data for only website features, and not adverts, AI or somewhat else. "
            ]],
            UserTable::COLUMNS["newsletter"][n]        => [ToggleField::class, [
                "description"   => "I want to stay informed with the help of the newsletter. ",
                "requiered"     => true
            ]],
            UserTable::COLUMNS["role"][n]              => [SelectMultipleField::class, [
                "description"   => "User role",
                "list"          => ["",""],
            ]]
        ];

        return $output;
    }
}
