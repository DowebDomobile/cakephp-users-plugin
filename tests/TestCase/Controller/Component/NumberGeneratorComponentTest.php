<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */

namespace Dwdm\Users\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use Dwdm\Users\Controller\Component\NumberGeneratorComponent;

/**
 * Class GeneratorComponent
 * @package Dwdm\Users\TestCase\Controller\Component
 */
class NumberGeneratorComponentTest extends TestCase
{
    /** @var ComponentRegistry */
    public $registry;

    public function setUp()
    {
        parent::setUp();

        $this->registry = new ComponentRegistry();
    }

    public function testDefaultLength()
    {
        $generator = new NumberGeneratorComponent($this->registry);

        $string = $generator->run();

        $this->assertEquals(4, strlen($string));
        $this->assertEquals(4, $generator->getConfig('length'));
    }

    public function testCustomLength()
    {
        $generator = new NumberGeneratorComponent($this->registry, ['length' => $length = 8]);

        $string = $generator->run();

        $this->assertEquals($length, strlen($string));
        $this->assertEquals($length, $generator->getConfig('length'));
    }
}