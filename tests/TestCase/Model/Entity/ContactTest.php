<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace TestCase\Model\Entity;

use Cake\TestSuite\TestCase;
use Dwdm\Users\Model\Entity\Contact;

/**
 * Class ContactTest
 * @package TestCase\Model\Entity
 */
class ContactTest extends TestCase
{
    /**
     * @dataProvider dataTestSetContact
     */
    public function testSetContact($type, $phone, $expected)
    {
        $contact = new Contact();

        $contact->type = $type;
        $contact->contact = $phone;
        $contact->replace = $phone;

        $this->assertEquals($expected, $contact->contact);
        $this->assertEquals($expected, $contact->replace);
    }

    public function dataTestSetContact()
    {
        return [
            ['phone', '+79139131313', '+79139131313'],
            ['phone', '+7(913)9131313', '+79139131313'],
            ['phone', '+7 (913) 913 13-13', '+79139131313'],
            ['email', 'email@example.com', 'email@example.com'],
            [null, 'email@example.com', 'email@example.com'],
            [null, '+7 (913) 913 13-13', '+7 (913) 913 13-13'],
        ];
    }
}