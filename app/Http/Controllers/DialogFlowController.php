<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DialogFlowController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->json()->all();
        $intent = $data['queryResult']['intent']['displayName'];
        $parameters = $data['queryResult']['parameters'];

        switch ($intent) {
            case 'Default Welcome Intent':
                return $this->handleWelcomeIntent();
            case 'Ver categorías':
                return $this->handleVerCategorias();
            case 'Ver productos por categorías':
                return $this->handleVerProductos($parameters);
            case 'Total de prodcutos por categorías':
                return $this->handleTotalCategorias($parameters);
            default:
                return response()->json(['fulfillmentText' => 'Lo siento, no entiendo esa solicitud.']);
        }
    }

    private function handleWelcomeIntent()
    {
        $responseText = "Hola, ¿en qué puedo ayudarte? Aquí tienes algunas opciones:\n1. Ver categorías\n2. Ver productos por categorías\n3. Total de prodcutos por categorías";
        return response()->json(['fulfillmentText' => $responseText]);
    }

    private function handleVerCategorias()
    {
        $client = new Client();
        $response = $client->get('https://backend-production-7023.up.railway.app/api/all-categories');
        $categories = json_decode($response->getBody(), true);

        $responseText = "Las categorías disponibles son:\n";
        foreach ($categories as $category) {
            $responseText .= "- " . $category['name'] . "\n";
        }

        return response()->json(['fulfillmentText' => $responseText]);
    }

    private function handleVerProductos($parameters)
    {
        $category = $parameters['category']; // Nombre o ID de la categoría recibido del bot
        $client = new Client();
        $response = $client->get('https://backend-production-7023.up.railway.app/api/get-products/' . urlencode($category));
        $products = json_decode($response->getBody(), true);

        $responseText = "Los productos disponibles en la categoría " . $category . " son:\n";
        foreach ($products as $product) {
            $responseText .= "- " . $product['name'] . "\n";
        }

        return response()->json(['fulfillmentText' => $responseText]);
    }

    private function handleTotalCategorias($parameters)
    {
        $client = new Client();
        $category = $parameters['category']; // Nombre o ID de la categoría recibido del bot
        $response = $client->get('https://backend-production-7023.up.railway.app/api/get-total-products/'.urlencode($category));
        $total = json_decode($response->getBody(), true)['total'];

        $responseText = "El total de categorías es: $total";
        return response()->json(['fulfillmentText' => $responseText]);
    }
}
