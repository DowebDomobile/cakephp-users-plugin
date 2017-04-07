<?php
namespace Dwdm\Users\Test\TestCase\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Users\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var Dwdm\\Users\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.dwdm/users.users',
        'plugin.dwdm/users.contacts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'Dwdm\Users\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->Users->initialize([]);
        $this->assertInstanceOf('\Cake\ORM\Association\HasMany', $this->Users->Contacts);
    }

    /**
     * Test validationDefault method
     *
     * @dataProvider dataProviderTestValidationDefaultSuccess
     *
     * @param array $data
     * @return void
     */
    public function testValidationDefaultSuccess($data)
    {
        $user = $this->Users->newEntity($data);

        $errors = $user->errors();

        $this->assertEmpty($errors);
    }

    public function dataProviderTestValidationDefaultSuccess()
    {
        return [
            [['is_active' => true]],
            [['is_active' => false]],
        ];
    }

    /**
     * Test validationDefault method
     *
     * @dataProvider dataProviderTestValidationDefaultFail
     *
     * @param array $data
     * @param array $expectedErrors
     * @return void
     */
    public function testValidationDefaultFail($data, $expectedErrors)
    {
        $user = $this->Users->newEntity($data);

        $errors = $user->errors();

        $this->assertNotEmpty($errors);
        $this->assertEquals($expectedErrors, $errors);
    }

    public function dataProviderTestValidationDefaultFail()
    {
        return [
                [[], ['is_active' => ['_required' => 'This field is required']]],
        ];
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $fake = function() {};

        $mockChecker = $this->createMock('\Cake\ORM\RulesChecker');
        $mockChecker->expects($this->once())->method('add')->with($this->equalTo($fake))->will($this->returnSelf());
        $mockChecker->expects($this->once())->method('isUnique')
                ->with($this->contains('username'))->will($this->returnValue($fake));

        /** @var RulesChecker $mockChecker */
        $rulesChecker = $this->Users->buildRules($mockChecker);

        $this->assertEquals($rulesChecker, $mockChecker);
    }
}
