<?php
/**
 * @copyright     Copyright (c) DowebDomobile (http://dowebdomobile.ru)
 */
namespace Dwdm\Users\Controller\Component;

use Cake\Controller\Component;

/**
 * Class GeneratorComponent
 */
class NumberGeneratorComponent extends Component
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $length = $this->getConfig('length');
        if (empty($length)) {
            $this->setConfig('length', 4);
        }
    }

    public function run()
    {
        $min = '1' . str_repeat('0', $this->getConfig('length') - 1);
        $max = str_repeat('9', $this->getConfig('length'));

        return rand($min, $max);
    }

}