<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsuariosMedicamentosTable;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Cake\Validation\Validator;

/**
 * App\Model\Table\UsuariosMedicamentosTable Test Case
 */
class UsuariosMedicamentosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsuariosMedicamentosTable
     */
    public $UsuariosMedicamentos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsuariosMedicamentos'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsuariosMedicamentos') ? [] : ['className' => UsuariosMedicamentosTable::class];
        $this->UsuariosMedicamentos = TableRegistry::getTableLocator()->get('UsuariosMedicamentos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsuariosMedicamentos);
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
            $this->UsuariosMedicamentos->getPrimaryKey(),
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
        $validator = $this->UsuariosMedicamentos->validationDefault($validator);
        $this->assertTrue($validator->hasField('codigo'));
        $this->assertTrue($validator->hasField('codigo_medicamentos'));
        $this->assertTrue($validator->hasField('codigo_usuario'));
        $this->assertTrue($validator->hasField('frequencia_dias'));
        $this->assertTrue($validator->hasField('frequencia_horarios'));
        $this->assertTrue($validator->hasField('uso_continuo'));
        $this->assertTrue($validator->hasField('dias_da_semana'));
        $this->assertTrue($validator->hasField('frequencia_uso'));
        $this->assertTrue($validator->hasField('horario_inicio_uso'));
        $this->assertTrue($validator->hasField('quantidade'));
        $this->assertTrue($validator->hasField('recomendacao_medica'));
        $this->assertTrue($validator->hasField('foto_receita'));
        $this->assertTrue($validator->hasField('frequencia_dias_intercalados'));
        $this->assertTrue($validator->hasField('periodo_tratamento_inicio'));
        $this->assertTrue($validator->hasField('periodo_tratamento_termino'));
        $this->assertTrue($validator->hasField('codigo_usuario_inclusao'));
        $this->assertTrue($validator->hasField('codigo_usuario_alteracao'));
        $this->assertTrue($validator->hasField('data_alteracao'));
        $this->assertTrue($validator->hasField('data_inclusao'));
    }
}
