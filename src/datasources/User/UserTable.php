<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Datasources\User;

use Atlas\Table\Table;

/**
 * @method UserRow|null fetchRow($primaryVal)
 * @method UserRow[] fetchRows(array $primaryVals)
 * @method UserTableSelect select(array $whereEquals = [])
 * @method UserRow newRow(array $cols = [])
 * @method UserRow newSelectedRow(array $cols)
 */
class UserTable extends Table
{
    const DRIVER = 'sqlsrv';

    const NAME = 'user';

    const COLUMNS = [
        'row_id' => array (
  'name' => 'row_id',
  'type' => 'int',
  'size' => 10,
  'scale' => 0,
  'notnull' => true,
  'default' => NULL,
  'autoinc' => true,
  'primary' => true,
  'options' => NULL,
),
        'insrted_at' => array (
  'name' => 'insrted_at',
  'type' => 'datetime',
  'size' => NULL,
  'scale' => NULL,
  'notnull' => true,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'updated_at' => array (
  'name' => 'updated_at',
  'type' => 'datetime',
  'size' => NULL,
  'scale' => NULL,
  'notnull' => true,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'email' => array (
  'name' => 'email',
  'type' => 'varchar',
  'size' => 255,
  'scale' => NULL,
  'notnull' => true,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'first_name' => array (
  'name' => 'first_name',
  'type' => 'varchar',
  'size' => 255,
  'scale' => NULL,
  'notnull' => false,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'last_name' => array (
  'name' => 'last_name',
  'type' => 'varchar',
  'size' => 255,
  'scale' => NULL,
  'notnull' => false,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'hashed_password' => array (
  'name' => 'hashed_password',
  'type' => 'varchar',
  'size' => 500,
  'scale' => NULL,
  'notnull' => false,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'rgpd' => array (
  'name' => 'rgpd',
  'type' => 'bit',
  'size' => NULL,
  'scale' => NULL,
  'notnull' => true,
  'default' => '0',
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'newsletter' => array (
  'name' => 'newsletter',
  'type' => 'bit',
  'size' => NULL,
  'scale' => NULL,
  'notnull' => true,
  'default' => '0',
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
        'role' => array (
  'name' => 'role',
  'type' => 'tinyint',
  'size' => 3,
  'scale' => 0,
  'notnull' => true,
  'default' => NULL,
  'autoinc' => false,
  'primary' => false,
  'options' => NULL,
),
    ];

    const COLUMN_NAMES = [
        'row_id',
        'insrted_at',
        'updated_at',
        'email',
        'first_name',
        'last_name',
        'hashed_password',
        'rgpd',
        'newsletter',
        'role',
    ];

    const COLUMN_DEFAULTS = [
        'row_id' => null,
        'insrted_at' => null,
        'updated_at' => null,
        'email' => null,
        'first_name' => null,
        'last_name' => null,
        'hashed_password' => null,
        'rgpd' => '0',
        'newsletter' => '0',
        'role' => null,
    ];

    const PRIMARY_KEY = [
        'row_id',
    ];

    const AUTOINC_COLUMN = 'row_id';

    const AUTOINC_SEQUENCE = null;
}
