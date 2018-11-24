<?php
/**
 * Created by PhpStorm.
 * User: fauss
 * Date: 11/2/2018
 * Time: 11:29 AM
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Collections\FieldCollection;
use App\Ipolitic\Nawpcore\Components\Field;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Fields\EmailField;
use App\Ipolitic\Nawpcore\Kernel;
use App\Server\Models\User\User;
use Jasny\HttpMessage\ServerRequest;
use PHPUnit\Framework\TestCase;

class FieldCollectionTest extends TestCase
{
    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testHowCollectionIsFilling()  : void
    {
        $kernel                 = new Kernel();
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection        = new FieldCollection($kernel, $record);
        $request                = new ServerRequest();
        $viewLogger             = new ViewLogger($kernel,$request);
        $fieldCollection        ->setViewLogger($viewLogger);
        $fieldCollection        ->fill();
        /**
         * Checking field collection elements counts
         */
        $this->assertEquals(
            $fieldCollection->count(),
            count($record->getArrayCopy()) - count(FieldCollection::blackListFields)
        );
        /**
         * Checking elements instances
         */
        $areAllFields = true;
        foreach($fieldCollection as $k => $v) {
            $areAllFields = $areAllFields && ($v instanceof Field);
            if (!$areAllFields) {
                break;
            }
        }
        $this->assertTrue($areAllFields);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testAddAdditionalValidityCheck() : void
    {
        $kernel = new Kernel();
        $record = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request            = new ServerRequest();
        $viewLogger         = new ViewLogger($kernel,$request);
        $fieldCollection    ->setViewLogger($viewLogger);
        $fieldCollection    ->fill();
        $fieldCollection    ->addAdditionalValidityCheck("email", function($value) {
            return "SOME ERROR TEST";
        });
        $this               ->assertFalse($fieldCollection->checkValidity());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testValidityCheck() : void
    {
        $kernel = new Kernel();
        $record = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection        = new FieldCollection($kernel, $record);
        $request                = new ServerRequest();
        $viewLogger             = new ViewLogger($kernel,$request);
        $fieldCollection        ->setViewLogger($viewLogger);
        $fieldCollection        ->fill();
        $this                   ->assertTrue($fieldCollection->checkValidity());
        /**
         * @var EmailField $emailField
         */
        $emailField             = $fieldCollection->getArrayCopy()["email"];
        $emailField             ->set("notAnEmail-iclud.com");
        $this                   ->assertFalse($fieldCollection->checkValidity());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testFieldCollectionRendering() : void
    {
        $kernel                 = new Kernel();
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(0);
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => $dateTime->format('Y-m-d H:i:s'),
            "inserted_at"       => $dateTime->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => $dateTime->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request            = new ServerRequest();
        $viewLogger         = new ViewLogger($kernel,$request);
        $fieldCollection    ->setViewLogger($viewLogger);
        $fieldCollection    ->fill();
        $fieldCollection    ->checkValidity();
        $statesArray        = $fieldCollection->getViews();
        $this               ->assertIsArray($statesArray);
        $html               = $viewLogger->renderOne($statesArray[0]);
        $this               ->assertTrue(
            (stristr($html, "name=\"email\"") !== false) && (stristr($html, "has-error") === false)
        );
        /**
         * @var EmailField $emailField
         */
        $emailField         = $fieldCollection->getArrayCopy()["email"];
        $emailField         ->set("notAnEmail-iclud.com");
        $fieldCollection    ->checkValidity();
        $html               = $viewLogger->renderOne($fieldCollection->getViews()[0]);
        $this               ->assertTrue(stristr($html, "has-error") !== false);
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testEqualDatabase() : void
    {
        $kernel                 = new Kernel();
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(0);
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => $dateTime->format('Y-m-d H:i:s'),
            "inserted_at"       => $dateTime->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => $dateTime->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request            = new ServerRequest();
        $viewLogger         = new ViewLogger($kernel,$request);
        $fieldCollection    ->setViewLogger($viewLogger);
        $fieldCollection    ->fill();
        $fieldCollection    ->checkValidity();
        $this               ->assertTrue($fieldCollection->equalDatabase());
        /**
         * @var EmailField $emailField
         */
        $emailField         = $fieldCollection->getArrayCopy()["email"];
        $emailField         ->set("ThisFieldNoLongerEqualTheDatabaseONe");
        $this               ->assertFalse($fieldCollection->equalDatabase());
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testSave() : void
    {
        $kernel                 = new Kernel();
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "email"             => "test@icloud.com",
            "birth_day"         => \DateTime::createFromFormat('U', 0)->format('Y-m-d H:i:s'),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request            = new ServerRequest();
        $viewLogger         = new ViewLogger($kernel,$request);
        $fieldCollection    ->setViewLogger($viewLogger);
        $fieldCollection    ->fill();
        /**
         * @var EmailField $emailField
         */
        $emailField         = $fieldCollection->getArrayCopy()["email"];
        $emailField         ->set("ThisFieldNoLongerEqualTheDatabaseONe");
        $fieldCollection    ->save();
        $this               ->assertTrue($fieldCollection->equalDatabase());
    }
}