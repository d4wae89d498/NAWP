<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 16/11/18
 * Time: 14:56
 */

namespace App\Server\Models;

use App\Ipolitic\Nawpcore\Components\SQL;
use App\Ipolitic\Nawpcore\Fields\DateField;
use App\Ipolitic\Nawpcore\Fields\DatetimeField;
use App\Ipolitic\Nawpcore\Fields\EmailField;
use App\Ipolitic\Nawpcore\Fields\PinField;
use App\Ipolitic\Nawpcore\Fields\PlaceField;
use App\Ipolitic\Nawpcore\Fields\SelectMultipleField;
use App\Ipolitic\Nawpcore\Fields\SelectOneField;
use App\Ipolitic\Nawpcore\Fields\TextField;
use App\Ipolitic\Nawpcore\Fields\ToggleField;
use App\Ipolitic\Nawpcore\Kernel;
use App\Server\Models\Categorie\Categorie;
use App\Server\Models\Content\Content;
use App\Server\Models\ContentsCategory\ContentsCategory;
use App\Server\Models\ContentsCategory\ContentsCategoryRecord;
use App\Server\Models\User\UserRecord;
use App\Server\Models\User\UserTable;
use Atlas\Mapper\Record;

class ModelsFields
{
    public static function getModelsFields(Kernel &$kernel): array
    {
        if (!defined("n")) {
            define("n", "name");
        }
        $output = [];
        /**
         *  USER FIELDS
         **/
        /**
         * @var SQL
         */
        $atlas = $kernel->atlas;
        $list = [];
        if (intval(getenv("ENABLE_DATABASE")) === 1) {
            $roleCategory = $atlas
                ->select(Categorie::class)
                ->where("title = ", "roles")
                ->fetchRecord()
                ->row_id;

            /**
             * @var ContentsCategoryRecord[]
             */
            $allRoles = $atlas
                ->select(ContentsCategory::class)
                ->where("categorie_id = ", $roleCategory)
                ->fetchRecords();
            /**
             * @var Record $role
             */
            foreach ($allRoles as $k => $role) {
                $allRoles[$k] = $atlas->select(Content::class)
                    ->where("row_id = ", $role->getArrayCopy()["content_id"])
                    ->fetchRecord();
            }
            /**
             * @var Record $v
             */
            foreach ($allRoles as $k => $v) {
                $list[] = $v->getArrayCopy()["title"];
            }

        } else {
            $list = [0 => "admin", 1 => "user"];
        }
        $minAge = 18;
        $minFirstNameLength = $minLastNameLength = 3;
        $maxFirstNameLength = $maxLastNameLength = 255;
        $minPasswordLength = 4;
        $maxPasswordLength = 6;
        $output[UserRecord::class] = [
            UserTable::COLUMNS["email"][n]             => [EmailField::class, [
                "icon"          => "fa fa-envelope-o",
                "placeHolder"   => "Email"
            ]],
            UserTable::COLUMNS["birth_day"][n]         => [DatetimeField::class, [
                "icon"          => "fa fa-birthday-cake",
                "range"         => [-2208988800, time() - $minAge * 365 * 24 * 60 * 60]
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
                "length"         => [$minPasswordLength, $maxPasswordLength],
                "usePwdHash"    => true,
                "numOnly"       => true
            ]],
            UserTable::COLUMNS["rgpd"][n]              => [ToggleField::class, [
                "icon"          => "fa fa-legal",
                "description"   => "I accept the use of my data for only website features, and not adverts, AI or somewhat else. "
            ]],
            UserTable::COLUMNS["newsletter"][n]        => [ToggleField::class, [
                "icon"          => "fa fa-newspaper-o",
                "description"   => "I want to stay informed with the help of the newsletter. ",
                "requiered"     => true
            ]],
            UserTable::COLUMNS["role_id"][n]              => [SelectOneField::class, [
                "description"   => "User role",
                "list"          => $list,
            ]]
        ];

        return $output;
    }
}
