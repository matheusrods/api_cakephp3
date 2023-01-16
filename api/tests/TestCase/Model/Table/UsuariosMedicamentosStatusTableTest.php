<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosMedicamentosStatusTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\UsuariosMedicamentosStatusTable Test Case
 */
class UsuariosMedicamentosStatusTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuariosMedicamentosStatusTable
     */
    public $UsuariosMedicamentosStatus;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuariosMedicamentosStatus'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuariosMedicamentosStatus') ? [] : ['className' => UsuariosMedicamentosStatusTable::class];
        $this->UsuariosMedicamentosStatus = TableRegistry::getTableLocator()->get('UsuariosMedicamentosStatus', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuariosMedicamentosStatus);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertEquals(
            'codigo',
            $this->UsuariosMedicamentosStatus->getPrimaryKey(),
            'The [App]Table default primary key is expected to be `codigo`.'
        );
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $validator = new Validator();
        $validator = $this->UsuariosMedicamentosStatus->validationDefault($validator);
        $this->assertTrue($validator->hasField('codigo'));
        $this->assertTrue($validator->hasField('codigo_usuario_medicamento'));
        $this->assertTrue($validator->hasField('data_hora_uso'));
        $this->assertTrue($validator->hasField('codigo_usuario_inclusao'));
        $this->assertTrue($validator->hasField('data_inclusao'));
    }
}
