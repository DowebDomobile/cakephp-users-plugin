<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Users\Test\TestCase\Model\Entity;

use Users\Model\Entity\Contact;

/**
 * Class ContactTest
 * @package Users\Test\TestCase\Model\Entity
 */
class ContactTest extends \PHPUnit_Framework_TestCase
{
    public function testContactSetToConfirm()
    {
        $expectedContact = 'email';

        $contact = new Contact();

        $contact->contact = $expectedContact;

        $this->assertEquals($expectedContact, $contact->replace);
        $this->assertEquals(null, $contact->contact);
        $this->assertNotNull($contact->code);
    }

    public function testContactConfirmNewContact()
    {
        $expectedContact = 'email';

        $contact = new Contact(['replace' => $expectedContact, 'code' => 'code']);

        $contact->contact = $expectedContact;

        $this->assertEquals($expectedContact, $contact->contact);
        $this->assertEquals(null, $contact->replace);
        $this->assertNull($contact->code);
    }

    public function testContactUpdateExistsContact()
    {
        $newContact = 'email';
        $oldContact = 'exists';

        $contact = new Contact(['contact' => $oldContact], ['useSetters' => false]);

        $contact->contact = $newContact;

        $this->assertEquals($newContact, $contact->replace);
        $this->assertEquals($oldContact, $contact->contact);
        $this->assertNotNull($contact->code);
    }

    public function testContactConfirmExistsContact()
    {
        $newContact = 'email';
        $oldContact = 'exists';

        $contact = new Contact(['contact' => $oldContact, 'replace' => $newContact, 'code' => 'code'], ['useSetters' => false]);

        $contact->contact = $newContact;

        $this->assertEquals($newContact, $contact->contact);
        $this->assertEquals(null, $contact->replace);
        $this->assertNull($contact->code);
    }

    public function testContactTryConfirmIfCodeEmpty()
    {
        $newContact = 'email';
        $oldContact = 'exists';

        $contact = new Contact(['contact' => $oldContact, 'replace' => $newContact], ['useSetters' => false]);

        $contact->contact = $newContact;

        $this->assertEquals($oldContact, $contact->contact);
        $this->assertEquals($newContact, $contact->replace);
        $this->assertNotNull($contact->code);
    }
}
 