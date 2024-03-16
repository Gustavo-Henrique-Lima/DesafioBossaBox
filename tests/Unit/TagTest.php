<?php

namespace Tests\Feature\Unit;

use App\Models\Tag;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TagTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se a criação de uma tag com um nome válido é bem-sucedida.
     *
     * @return void
     */
    public function testCreateTagWithValidName()
    {
        // Cria uma nova tag com um nome válido
        $tag = Tag::create([
            'id' => 1,
            'name' => 'Valid Tag',
        ]);

        // Verifica se a tag foi criada corretamente
        $this->assertDatabaseHas('tb_tags', [
            'id' => 1,
            'name' => 'Valid Tag',
        ]);
    }

    /**
     * Testa se a criação de uma tag com um nome inválido lança uma exceção.
     *
     * @return void
     */
    public function testCreateTagWithInvalidName()
    {
        // Tenta criar uma nova tag com um nome inválido
        $this->expectException(\InvalidArgumentException::class);
        Tag::create([
            'id' => 1,
            'name' => 'Inv', // Nome inválido, menos de 3 caracteres
        ]);
    }

    /**
     * Testa se a atualização de uma tag com um nome válido é bem-sucedida.
     *
     * @return void
     */
    public function testUpdateTagWithValidName()
    {
        // Cria uma tag inicialmente com nome válido
        $tag = Tag::create([
            'id' => 1,
            'name' => 'Initial Tag',
        ]);

        // Atualiza o nome da tag para um novo nome válido
        $tag->name = 'Updated Tag';
        $tag->save();

        // Verifica se a tag foi atualizada corretamente
        $this->assertDatabaseHas('tb_tags', [
            'id' => 1,
            'name' => 'Updated Tag',
        ]);
    }

    /**
     * Testa se a atualização de uma tag com um nome inválido lança uma exceção.
     *
     * @return void
     */
    public function testUpdateTagWithInvalidName()
    {
        // Cria uma tag inicialmente com nome válido
        $tag = Tag::create([
            'id' => 1,
            'name' => 'Initial Tag',
        ]);

        // Tenta atualizar o nome da tag para um nome inválido
        $this->expectException(\InvalidArgumentException::class);
        $tag->name = 'Inv'; // Nome inválido, menos de 3 caracteres
        $tag->save();
    }
}
