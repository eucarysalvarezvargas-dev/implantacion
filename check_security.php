<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Verificando Preguntas de Seguridad para User ID 7 ===\n\n";

$respuestas = \App\Models\RespuestaSeguridad::where('user_id', 7)
    ->with('pregunta')
    ->get();

if ($respuestas->isEmpty()) {
    echo "❌ NO HAY PREGUNTAS DE SEGURIDAD CONFIGURADAS\n";
} else {
    echo "✓ Encontradas " . $respuestas->count() . " preguntas de seguridad:\n\n";
    
    foreach ($respuestas as $index => $r) {
        echo "Pregunta " . ($index + 1) . ":\n";
        echo "  ID: " . $r->pregunta_id . "\n";
        echo "  Texto: " . $r->pregunta->pregunta . "\n";
        echo "  Hash guardado: " . $r->respuesta_hash . "\n";
        echo "  ---\n";
    }
}
