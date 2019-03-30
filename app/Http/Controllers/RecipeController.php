<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;

class RecipeController extends Controller
{
    public function read($filter = 'all', $param = null){
        if($filter == 'all'){
            $recipes = Recipe::all();
        } else if ($filter == 'author'){
            $recipes = Recipe::where('author', $param)->get();
        } else {
            $recipes = [];
        }
        return $recipes;
    }

    public function store(Request $request)
    {
        $recipe = new Recipe;
        $recipe->title = $request->title;
        $recipe->author = $request->author;
        $recipe->ingredients = $request->ingredients;
        $recipe->instructions = $request->instructions;
        $recipe->categories = $request->category;


		if($request->image != ''){
			$exploded = explode(',', $request->image);
			$decoded = base64_decode($exploded[1]);
			if(str_contains($exploded[0], 'jpg')){
				$extention = 'jpg';
			} else if (str_contains($exploded[0], 'jpeg')) {
				$extention = 'jpeg';
			} else {
				$extention = 'png';
			}
			$fileName = str_random().'.'.$extention;
			$path = public_path().'/recipeImages/'.$fileName;
			if($extention){
				file_put_contents($path, $decoded);
				$recipe->photo = 'recipeImages/'.$fileName;
			}
        }

        $recipe->save();
        return response([
            'status' => 'success',
            'data' => $recipe
           ], 200);
     }
}