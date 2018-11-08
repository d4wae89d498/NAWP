<?php
/**
 * This file was generated by Atlas. Changes will be overwritten.
 */
declare(strict_types=1);

namespace App\Server\Models\Content;

use Atlas\Table\Row;

/**
 * @property mixed $row_id int(10,0) NOT NULL
 * @property mixed $inserted_at datetime NOT NULL
 * @property mixed $updated_at datetime NOT NULL
 * @property mixed $title varchar(255)
 * @property mixed $content text(65535)
 * @property mixed $author int(10,0)
 * @property mixed $draft bit(1)
 * @property mixed $parent int(10,0)
 */
class ContentRow extends Row
{
    protected $cols = [
        'row_id' => null,
        'inserted_at' => null,
        'updated_at' => null,
        'title' => null,
        'content' => null,
        'author' => null,
        'draft' => null,
        'parent' => null,
    ];
}
