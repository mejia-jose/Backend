<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;

class ProductsController extends Controller
{
    //Función que permite registrar o actualizar productos
    public function createAndUpdateProduct(Request $request)
    {
        try
        {
            // Validar los datos de entrada
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'quantity' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id'
            ]);

            //Permite validar que no se registren productos con el mismo nombre
            $product = Products::where('name', $request->input('name'))->where('category_id',$request->input('category_id'))->first();
            $id = $request->input('id');
            if ($product && !$id)
            {
                return response()->json(['message' => 'No se ha realizado el registro porque ya existe un producto con el nombre "'.$request->input('name').'" para esa categoría.'], 400);
            }

            // Crear un nuevo producto
            $values = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'quantity' => $request->input('quantity'),
                'category_id' => $request->input('category_id'),
                'created_at' => now(),
                'updated_at' => now()
            ];
            $conditions = ['id' => $id];
            Products::updateOrInsert($conditions, $values);

            $msj = (!$request->input('id')) ? 'Producto creado correctamente ' : 'El producto con ID '.$id. ' fue actualizado correctamente.';

            return response()->json(['message' => $msj], 201);

        } catch (\Exception $e)
        {
            $msj = (!$request->input('id')) ? 'Ha ocurrido un error al registrar el producto.' : 'Ha ocurrido un error al actualizar el producto.';
            return response()->json(['message' => $msj, 'error' => $e->getMessage()], 500);
        }
    }

    //Función que permite obtener un producto de acuerdo a su categoria
    public function getProducts($categoryType)
    {
        try 
        {
            if (is_numeric($categoryType))//Se buscan los productos por id de categorias
            {
                $products = Products::where('category_id', $categoryType)->get();
            } else 
            {
                //Se buscan productos por nombre de categoría
                $category= Category::where('name', $categoryType)->first();

                if ($category)
                {
                    $products = Products::where('category_id', $category->id)->get();
                } else
                {
                    return response()->json(['message' => 'Por favor verifique que si exista la categoría.'], 404);
                }
            }
            $count = $products->count();
            if($count > 0)
            {
                return response()->json(['products' => $products], 200);
            }else
            {
                return response()->json(['message' => "No se encontraron productos para esta categoría, por favor ingresa uno nuevo."], 200);
            }
        
        } catch (\Exception $e)
        {
         return response()->json(['message' => 'Ha ocurrido un error al obtener los productos', 'error' => $e->getMessage()], 500);
        }
    }

    //Función que permite obtener el total de productos de una categoría
    public function getTotalProductsByCategory($categoryType)
    {
        try 
        {
            if (is_numeric($categoryType))//Se buscan los productos por id de categorias
            {
                $products = Products::where('category_id', $categoryType)->get();
            } else 
            {
                //Se buscan productos por nombre de categoría
                $category= Category::where('name', $categoryType)->first();

                if ($category)
                {
                    $products = Products::where('category_id', $category->id)->get();
                } else
                {
                    return response()->json(['message' => 'Por favor verifique que si exista la categoría.'], 404);
                }
            }
            $count = $products->count();
            if($count > 0)
            {
                return response()->json(['Total' => $count], 200);
            }else
            {
                return response()->json(['Total' => 0], 200);
            }
        
        } catch (\Exception $e)
        {
         return response()->json(['message' => 'Ha ocurrido un error al obtener el total de productos de esta categoría.', 'error' => $e->getMessage()], 500);
        }
    }

    //Función que permite la eliminación de categorias por medio del ID
    public function deleteProducts($productsType)
    {
        try
        {
            //Permite buscar la categoría por su ID
            $products = Products::find($productsType);

            //Validación que permite identificar si el producto existe
            if (!$products)
            {
                return response()->json(['message' => 'El producto que intenta eliminar no fue encontrado.'], 404);
            }

            $products->delete();

            // Devolver una respuesta de éxito
            return response()->json(['message' => 'El producto ha sido elimanado de forma exitosa.'], 200);

        } catch (\Exception $e)
        {
            return response()->json(['message' => 'Ha ocurrido un error al interntar eliminar el producto.', 'error' => $e->getMessage()], 200);
        }
    }
}
