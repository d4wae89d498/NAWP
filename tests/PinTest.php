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
use App\Ipolitic\Nawpcore\Fields\PinField;
use App\Ipolitic\Nawpcore\Kernel;
use App\Server\Models\User\User;
use PHPUnit\Framework\TestCase;

class PinTest extends TestCase
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
            "role_id"              => 1
        ]);
        $fieldCollection    = new FieldCollection($kernel, $record);
        $request                = new \Jasny\HttpMessage\ServerRequest();
        $viewLogger             = new ViewLogger($kernel,$request);
        $fieldCollection        ->setViewLogger($viewLogger);
        $fieldCollection->fill();
        /**
         * @var PinField $pinField
         */
        $pinField = $fieldCollection->getArrayCopy()["hashed_password"];
        $this->assertTrue($pinField->checkValidity() === "");
        $pinField->prop["numOnly"] = true;
        $pinField->prop["length"] = [4, 99];
        $pinField->set("ComposedWithLetters");
        $this->assertFalse($pinField->checkValidity() === "");
        $pinField->prop["numOnly"] = false;
        $pinField->prop["length"] = [4, 99];
        $pinField->set("ComposedWithLetters");
        $this->assertTrue($pinField->checkValidity() === "");
        $pinField->prop["numOnly"] = true;
        $pinField->prop["length"] = [4, 5];
        $pinField->set(str_repeat("*", $pinField->prop["length"][1] + 1));
        $this->assertFalse($pinField->checkValidity() === "");


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
            "role_id"              => 1
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