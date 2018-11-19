<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 19/11/18
 * Time: 17:16
 */

namespace App\Tests;


use App\Ipolitic\Nawpcore\Collections\FieldCollection;
use App\Ipolitic\Nawpcore\Components\ViewLogger;
use App\Ipolitic\Nawpcore\Fields\DateField;
use App\Ipolitic\Nawpcore\Kernel;
use App\Server\Models\User\User;
use PHPUnit\Framework\TestCase;

class DateFieldTest extends TestCase
{
    /**
     * @return void
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testValidity() : void
    {
        $birthDat = (new \DateTime());
        $birthDat->setTimestamp(0);
        $kernel                 = new Kernel();
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format("Y-m-d"),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format("Y-m-d H:i:s"),
            "email"             => "test@icloud.com",
            "birth_day"         => $birthDat->format("Y-m-d H:i:s"),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request                = new \Jasny\HttpMessage\ServerRequest();
        $viewLogger             = new ViewLogger($kernel,$request);
        $fieldCollection        ->setViewLogger($viewLogger);
        $fieldCollection->fill();
        /**
         * @var DateField $birthDayField
         */
        $birthDayField = $fieldCollection->getArrayCopy()["birth_day"];
        $this->assertTrue($birthDayField->checkValidity() === "");

        $birthDayField->set(\DateTime::createFromFormat('U', time())->format("Y-m-d"));
        $this->assertFalse($birthDayField->checkValidity() === "");
    }

    /**
     * @throws \App\Ipolitic\Nawpcore\Exceptions\InvalidImplementation
     * @throws \App\Ipolitic\Nawpcore\Exceptions\SetViewLoggerNotCalled
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testRendering() : void
    {
        $birthDat = (new \DateTime());
        $birthDat->setTimestamp(0);
        $kernel                 = new Kernel();
        $record                 = $kernel->atlas->newRecord(User::class, [
            "updated_at"        => \DateTime::createFromFormat('U', 0)->format('Y-m-d'),
            "inserted_at"       => \DateTime::createFromFormat('U', 0)->format('Y-m-d'),
            "email"             => "test@icloud.com",
            "birth_day"         => $birthDat->format("Y-m-d"),
            "birth_place"       => "London, United Kingdom",
            "first_name"        => "john",
            "last_name"         => "doe",
            "hashed_password"   => "5684",
            "rgpd"              => true,
            "newsletter"        => true,
            "role"              => 0
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request                = new \Jasny\HttpMessage\ServerRequest();
        $viewLogger             = new ViewLogger($kernel,$request);
        $fieldCollection        ->setViewLogger($viewLogger);
        $fieldCollection->fill();
        $fieldCollection->checkValidity();
        $birthDayField = $fieldCollection["birth_day"];
        $html = $viewLogger->renderOne($birthDayField->getViews());
        // checking that there is not error with a correct value
        $this->assertTrue(stristr($html, "has-error") === false);
        /**
         * @var DateField $birthDayField
         */
        $birthDayField = $fieldCollection["birth_day"];
        $birthDayField->set(\DateTime::createFromFormat('U', time())->format("Y-m-d"));
        $fieldCollection->checkValidity();
        $html = $viewLogger->renderOne($birthDayField->getViews());
        // checking that there is one error when there is a failure
        $this->assertTrue(stristr($html, "has-error") !== false);

    }
}