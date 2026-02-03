<?php

require 'vendor/autoload.php';

// Bootstrap Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== CIUDADES EN BASE DE DATOS ===\n";

try {
    $cities = \App\Models\Cities::limit(20)->get(['cities_id', 'name', 'states_id']);

    foreach ($cities as $city) {
        echo "ID: {$city->cities_id} - {$city->name} (Estado: {$city->states_id})\n";
    }

    echo "\nTotal de ciudades encontradas: " . $cities->count() . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== PROBANDO BÚSQUEDA ===\n";

// Probar búsqueda con las primeras ciudades
if ($cities->count() > 0) {
    $firstCity = $cities->first();
    echo "Probando búsqueda con: " . $firstCity->name . "\n";

    $searchResults = \App\Models\Cities::where('name', 'LIKE', '%' . $firstCity->name . '%')->get(['cities_id', 'name']);
    echo "Resultados encontrados: " . $searchResults->count() . "\n";

    foreach ($searchResults as $result) {
        echo "- {$result->name}\n";
    }
}
