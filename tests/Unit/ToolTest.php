<?php

namespace Tests\Feature\Unit;

use App\Models\Tool;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se a criação de uma ferramenta com atributos válidos é bem-sucedida.
     *
     * @return void
     */
    public function testCreateToolWithValidAttributes()
    {
        // Cria uma nova ferramenta com atributos válidos
        $tool = Tool::create([
            'id' => 1,
            'title' => 'Valid Tool',
            'link' => 'https://example.com',
            'description' => 'A valid tool description.',
        ]);

        // Verifica se a ferramenta foi criada corretamente
        $this->assertDatabaseHas('tb_tools', [
            'id' => 1,
            'title' => 'Valid Tool',
            'link' => 'https://example.com',
            'description' => 'A valid tool description.',
        ]);
    }

    /**
     * Testa se a criação de uma ferramenta com atributos inválidos lança uma exceção.
     *
     * @return void
     */
    public function testCreateToolWithInvalidAttributes()
    {
        // Tenta criar uma nova ferramenta com atributos inválidos
        $this->expectException(\InvalidArgumentException::class);
        Tool::create([
            'id' => 1,
            'title' => 'Inv', // Título inválido, menos de 3 caracteres
            'link' => 'https://example.com',
            'description' => 'A valid tool description.',
        ]);
    }

    /**
     * Testa se a atualização de uma ferramenta com atributos válidos é bem-sucedida.
     *
     * @return void
     */
    public function testUpdateToolWithValidAttributes()
    {
        // Cria uma ferramenta inicialmente com atributos válidos
        $tool = Tool::create([
            'id' => 1,
            'title' => 'Initial Tool',
            'link' => 'https://example.com',
            'description' => 'A valid tool description.',
        ]);

        // Atualiza os atributos da ferramenta para novos valores válidos
        $tool->title = 'Updated Tool';
        $tool->save();

        // Verifica se a ferramenta foi atualizada corretamente
        $this->assertDatabaseHas('tb_tools', [
            'id' => 1,
            'title' => 'Updated Tool',
            'link' => 'https://example.com',
            'description' => 'A valid tool description.',
        ]);
    }
}
