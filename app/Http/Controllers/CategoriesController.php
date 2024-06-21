<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Products;

class CategoriesController extends Controller
{
    //Función que permite la creación de categorias
    public function createAndUpdateCategory(Request $request)
    {
        try
        {
            // Validar los datos de entrada
            $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
            ]);

            //Permite validafr que no se registren categorias con el mismo nombre
            $category = Category::where('name', $request->input('name'))->first();
            $id = $request->input('id');
            if ($category && !$id)
            {
                return response()->json(['message' => 'No se ha realizado el registro porque ya existe una categoría con el nombre "'.$request->input('name').'"'], 400);
            }

            // Crear una nueva categoría
            $values = [
                'name' => $request->input('name'),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $conditions = ['id' => $id];
            Category::updateOrInsert($conditions, $values);

            $msj = (!$request->input('id')) ? 'Categoría creada correctamente' : 'La categoría con ID '.$id. ' fue actualida correctamente.';

            return response()->json(['message' => $msj], 201);

        } catch (\Exception $e)
        {
            return response()->json(['message' => 'Ha ocurrido un error al registrar la categoría.', 'error' => $e->getMessage()], 500);
        }
    }

    //Función que permite obtener todas las categorias
    public function getCategories($idCategory = 0)
    {
        try 
        {
            if($idCategory > 0)
            {
                $categories = Category::find($idCategory);
            }else
            {
                $categories = Category::all();
                return response()->json(['categories' => $categories], 200);
            }

            $count = $categories->count();
            if($count > 0)
            {
                return response()->json(['categories' => $categories], 200);
            }else
            {
                return response()->json(['message' => "No se encontraron categorías, por favor ingresa una nueva."], 200);
            }
        
        } catch (\Exception $e)
        {
        return response()->json(['message' => 'Ha ocurrido un error al obtener las categorías', 'error' => $e->getMessage()], 500);
        }
    }

    //Función que permite la eliminación de categorias por medio del ID
    public function deleteCategory($idCategory)
    {
        try
        {
            //Se valida que el id sea número
            if(!is_numeric($idCategory))
            {
                return response()->json(['message' => 'El ID '.$idCategory.' dese ser un valor numérico.'], 404);
            }

            //Permite buscar la categoría por su ID
            $category = Category::find($idCategory);
            $products = Products::where('category_id',$idCategory);

            if($products->count() > 0)
            {
                return response()->json([
                    'message' => 'No se puede eliminar la categoría con ID ' . $idCategory . ' porque tiene ' . $products->count() . ' productos registrados.'
                ], 404);                
            }

            //Validación que permite identificar si la categoria existe
            if (!$category)
            {
                return response()->json(['message' => 'La categoría con ID '.$idCategory.' no fue encontrada.'], 404);
            }

            $category->delete();

            // Devolver una respuesta de éxito
            return response()->json(['message' => 'La categoría con ID '.$idCategory. ' ha sido elimanado de forma exitosa.'], 200);

        } catch (\Exception $e)
        {
            return response()->json(['message' => 'Ha ocurrido un error al interntar eliminar la categoría con ID '.$idCategory, 'error' => $e->getMessage()], 200);
        }
    }
}

