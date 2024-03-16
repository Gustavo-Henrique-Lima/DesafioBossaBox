<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ToolController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Tool"},
     *     summary="Get all Tools",
     *     description="This endpoint returns a collection of Tools filtered by Tag.",
     *     path="/api/tools",
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(
     *             @OA\Property(type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example="1"),
     *                     @OA\Property(property="title", type="string", example="Value 1"),
     *                     @OA\Property(property="link", type="string", example="www.value1.com"),
     *                     @OA\Property(property="description", type="string", example="Value 1 is a Tool very powerful"),
     *                     @OA\Property(property="tags", type="array", 
     *                      @OA\Items(
     *                             @OA\Property(property="name", type="string", example="Tag"),
     *                      )
     *                    ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getTools(Request $request)
    {
        $tag = $request->input("tag");

        if ($tag) {

            $tools = Tool::whereHas("tags", function ($query) use ($tag) {
                $query->where("name", $tag);
            })->with(["tags" => function ($query) use ($tag) {
                $query->where("name", $tag);
            }])->get();

            //A função makeHidden equivale ao @JsonIgnore
            $tools->each(function ($tool) {
                $tool->tags->makeHidden(["id", "created_at", "updated_at", "pivot"]);
            });

            $tools->makeHidden(["created_at", "updated_at"]);
        } else {

            $tools = Tool::with(["tags" => function ($query) {
                $query->select("name");
            }])->get();

            $tools->each(function ($tool) {
                $tool->tags->makeHidden("pivot");
            });

            //A função makeHidden equivale ao @JsonIgnore
            $tools->makeHidden(["created_at", "updated_at"]);
        }

        Log::info("Tools returned with sucess ", ["tool" => $tools->toArray()]);
        return response()->json($tools, 200);
    }


    /**
     * @OA\Post(
     *     tags={"Tool"},
     *     summary="Save a new Tool",
     *     description="This endpoint saves a new Tool in the database.",
     *     path="/api/tools",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="json",
     *             @OA\Schema(
     *                 required={"title", "link", "description", "tags"},
     *                 @OA\Property(property="title", type="string", example="Value 1"),
     *                 @OA\Property(property="link", type="string", example="www.value1.com"),
     *                 @OA\Property(property="description", type="string", example="Value 1 is a powerful tool"),
     *                 @OA\Property(property="tags", type="array", 
     *                      @OA\Items(
     *                             @OA\Property(property="name", type="string", example="Tag"),
     *                      )
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Value 1"),
     *             @OA\Property(property="link", type="string", example="www.value1.com"),
     *             @OA\Property(property="description", type="string", example="Value 1 is a powerful tool"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="Tag1")),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="O atributo da ferramenta deve ter mais de 3 caracteres e não pode ser nulo."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="As tags a seguir não existem: value 1, value 2, por favor use as tags já cadastradas."),
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $existingTags = Tag::whereIn("name", $data["tags"])->pluck("name")->toArray();
            $missingTags = array_diff($data["tags"], $existingTags);

            if (!empty($missingTags)) {
                throw new ModelNotFoundException("As tags a seguir não existem: " . implode(", ", $missingTags) . ", por favor use as tags já cadastradas.");
            }

            $tool = Tool::create([
                "title" => $data["title"],
                "link" => $data["link"],
                "description" => $data["description"],
            ]);

            foreach ($data["tags"] as $tagName) {
                $tag = Tag::where("name", $tagName)->first();
                $tool->tags()->attach($tag->id);
            }

            $tool->makeHidden(["created_at", "updated_at"]);
            $tool->tags->each(function ($tag) {
                $tag->makeHidden(["created_at", "updated_at", "pivot"]);
            });

            Log::info("Tool created with sucess ", ["tool" => $tool->toArray()]);
            return response()->json($tool, 201);
        } catch (\InvalidArgumentException $e) {
            Log::error("Erro ao tentar cadastrar uma Tool: Erro nos atributos.");
            return response()->json(["error" => $e->getMessage()], 422);
        } catch (\Illuminate\Database\QueryException  $e) {
            Log::error("Erro ao tentar cadastrar uma Tool: Já existe uma Tool cadastrada com esse nome.");
            return response()->json(["error" => "Já existe uma Tool cadastrada com esse nome."], 409);
        } catch (ModelNotFoundException $e) {
            Log::error("Erro ao tentar cadastrar uma Tool: A(s) tag(s) não existe(m)");
            return response()->json(["error" => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Tool"},
     *     summary="Delete Tool",
     *     description="This endpoint delete a Tool from the database.",
     *     path="/api/tools/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the resource",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Ferramenta não encontrada."),
     *         )
     *     )
     * )
     */
    public function delete($id)
    {
        $tool = Tool::find($id);
        if ($tool == null) {
            Log::error("Erro ao tentar deletar uma Tool: Nenhuma Tool com o id " . $id . " foi encontrada.");
            return response()->json(["error" => "Ferramenta não encontrada."], 404);
        }
        $tool->tags()->detach();
        $tool->delete();
        Log::info("Tool deleted with sucess");
        return response()->json("", 204);
    }
}
