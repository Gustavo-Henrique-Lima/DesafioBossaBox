<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    public function getAllTags(){
        $tags=Tag::all();
        return response()->json($tags,200);
    }

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

    public function delete($id){
        $tag=Tag::find($id);
        if($tag==null){
            return response()->json(["error" => "Tag não encontrada"], 404);
        }else{
            $tag->delete();
            return response()->json("",204);
        }
    }
}
