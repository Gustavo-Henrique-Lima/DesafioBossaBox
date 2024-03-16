<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ToolControllerTest extends TestCase
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
     * Testa o método getTools.
     *
     * @return void
     */
    public function testGetTools()
    {
        // Cenário 1: Nenhuma ferramenta no banco de dados
        $response = $this->get("/api/tools");
        $response->assertStatus(200)->assertJson([]);

        // Cenário 2: Algumas ferramenta existem no banco de dados
        Tool::factory()->count(3)->create();
        $response = $this->get("/api/tools");
        $response->assertStatus(200)
            ->assertJsonStructure([
                "*" => [
                    "title",
                    "link",
                    "description",
                ],
            ]);
    }

    /**
     * Testa o método store para criação de uma nova tool.
     *
     * @return void
     */
    public function testStore()
    {
        // Cenário 1: Dados válidos para criar uma nova tool
        $toolData = [
            "title" => "hotel144",
            "link" => "https://github.com/typicode/hotel",
            "description" => "Local app manager. Start apps within your browser, developer tool with local .localhost domain and https out of the box.",
            "tags" => [
                "Http2"
            ]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("/api/tools", $toolData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "title",
                "link",
                "description",
                "tags",
            ]);

        // Cenário 2: Tentativa de criar uma tool sem o campo obrigatório "name"
        $toolData = [
            "title" => "",
            "link" => "https://github.com/typicode/hotel",
            "description" => "Local app manager. Start apps within your browser, developer tool with local .localhost domain and https out of the box.",
            "tags" => [
                "Http2"
            ]
        ];
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("/api/tools", $toolData);

        $response->assertStatus(422)
            ->assertJson([
                "error" => "O(A) titulo da ferramenta deve ter mais de 3 caracteres e não pode ser nulo.",
            ]);

        $toolData1 = [
            "title" => "hotel144",
            "link" => "https://github.com/typicode/hotel",
            "description" => "Local app manager. Start apps within your browser, developer tool with local .localhost domain and https out of the box.",
            "tags" => [
                "Http2"
            ]
        ];
        // Cenário 3: Tentativa de criar uma tool sem informar o token
        $response = $this->withHeaders([
            'Authorization' => '',
        ])->post("/api/tools", $toolData);

        $response->assertStatus(401)
            ->assertJson([
                "error" => "Unauthorized",
            ]);
    }

    /**
     * Testa o método delete para deletar uma Tool existente.
     *
     * @return void
     */
    public function testDelete()
    {
        // Cenário 1: Deletar uma Tool existente
        $tool = Tool::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("/api/tools/" . $tool->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing("tb_tools", ["id" => $tool->id]);

        // Cenário 2: Tentativa de deletar uma tool que não existe sem passar o token
        $nonExistingId = 999;
        $response = $this->withHeaders([
            'Authorization' => '',
        ])->delete("/api/tools/" . $nonExistingId);
        $response->assertStatus(401)
            ->assertJson([
                "error" => "Unauthorized",
            ]);

        // Cenário 3: Tentativa de deletar uma tool que não existe passando o token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("/api/tools/" . $nonExistingId);
        $response->assertStatus(404)
            ->assertJson([
                "error" => "Ferramenta não encontrada.",
            ]);
    }
}
