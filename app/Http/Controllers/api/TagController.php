<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{

    /**
     * @OA\GET(
     *  tags={"Tag"},
     *  summary="Get all Tag",
     *  description="This endpoint return a collection of Tag.",
     *  path="/api/tags",
     *  @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(type="array", 
     *         @OA\Items(
     *              @OA\Property(property="name", type="string", example="Value 1"),
     *              @OA\Property(property="updated_at", type="date", example=null),
     *              @OA\Property(property="created_at", type="date", example="2024-03-08T23:04:11.000000Z")
     *          )
     *     )
     *    )
     *  )
     * )
     */
    public function getAllTags(){
        $tags=Tag::all();
        return response()->json($tags,200);
    }

     /**
     * @OA\POST(
     *  tags={"Tag"},
     *  summary="Save a new Tag",
     *  description="This endpoint save a new Tag at database.",
     *  path="/api/tags",
     *  @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="json",
     *          @OA\Schema(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="Value 1"),
     *          )
     *      ), 
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Created",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Value 1"),
     *       @OA\Property(property="updated_at", type="date", example=null),
     *       @OA\Property(property="created_at", type="date", example="2024-03-08T23:04:11.000000Z"),
     *    )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Unprocessable Content",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="O nome da categoria deve ter mais de 3 caracteres e não pode ser nulo."),
     *    )
     *  ),
     *  @OA\Response(
     *    response=409,
     *    description="Conflict",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Tag já cadastrada."),
     *    )
     *  )
     * )
     */
    public function store(Request $request){
        try {
            $data=$request->all();
            $data["created_at"]=now();
            $tag=Tag::create($data);

            return response()->json($tag,201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(["error" => $e->getMessage()], 422);
        } catch (\Illuminate\Database\QueryException  $e){
            return response()->json(["error" => "Tag já cadastrada"], 409);
        }
    }

    /**
     * @OA\DELETE(
     *  tags={"Tag"},
     *  summary="Delete Tag",
     *  description="This endpoint delete a Tag from database.",
     *  path="/api/tags/{id}",
     * @OA\Parameter(
     *   name="id",
     *   in="path",
     *   description="ID of the resource",
     *   required=true,
     *   @OA\Schema(
     *      type="integer",
     *       format="int64"
     *   )
     * ),
     *  @OA\Response(
     *    response=204,
     *    description="No content",
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Not found",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Tag não encontrada."),
     *    )
     *  )
     * )
     */
    public function delete($id){
        $tag=Tag::find($id);
        if($tag==null){
            return response()->json(["error" => "Tag não encontrada."], 404);
        }else{
            $tag->delete();
            return response()->json("",204);
        }
    }
}
