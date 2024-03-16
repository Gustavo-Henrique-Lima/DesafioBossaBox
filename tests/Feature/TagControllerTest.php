<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar um usuário para o teste
        $user = User::factory()->create();

        // Fazer login e obter o token
        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->token = $response->json('access_token');
    }

    /**
     * Testa o método getAllTags.
     *
     * @return void
     */
    public function testGetAllTags()
    {
        // Cenário 1: Nenhuma tag no banco de dados
        $response = $this->get("/api/tags");
        $response->assertStatus(200)->assertJson([]);

        // Cenário 2: Algumas tags existem no banco de dados
        Tag::factory()->count(3)->create();
        $response = $this->get("/api/tags");
        $response->assertStatus(200)
            ->assertJsonStructure([
                "*" => [
                    "name",
                ],
            ]);
    }

    /**
     * Testa o método store para criação de uma nova Tag.
     *
     * @return void
     */
    public function testStore()
    {
        // Cenário 1: Dados válidos para criar uma nova tag
        $tagData = [
            "name" => "New Tag",
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("/api/tags", $tagData);
        $response->assertStatus(201)
            ->assertJsonStructure([
                "name",
            ])->assertJson([
                "name" => "New Tag",
            ]);;
        $this->assertDatabaseHas("tb_tags", $tagData);

        // Cenário 2: Tentativa de criar uma tag sem o campo obrigatório "name"
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("/api/tags", []);
        $response->assertStatus(422)
            ->assertJson([
                "error" => "O nome da categoria deve ter mais de 3 caracteres e não pode ser nulo.",
            ]);

        // Cenário 2: Tentativa de criar uma tag sem passar o token
        $response = $this->withHeaders([
            'Authorization' => ' ',
        ])->post("/api/tags", $tagData);
        $response->assertStatus(401)
            ->assertJson([
                "error" => "Unauthorized",
            ]);
    }

    /**
     * Testa o método delete para deletar uma Tag existente.
     *
     * @return void
     */
    public function testDelete()
    {
        // Cenário 1: Deletar uma tag existente sem passar o token
        $tag = Tag::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => '',
        ])->delete("/api/tags/" . $tag->id);
        $response->assertStatus(401)
            ->assertJson([
                "error" => "Unauthorized",
            ]);

        // Cenário 2: Tentativa de deletar uma tag que não existe
        $nonExistingId = 999;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("/api/tags/" . $nonExistingId);
        $response->assertStatus(404)
            ->assertJson([
                "error" => "Tag não encontrada.",
            ]);

        // Cenário 3: Deletar uma tag existente
        $tag = Tag::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("/api/tags/" . $tag->id);
        $response->assertStatus(204);
    }
}
