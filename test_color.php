<?php

$target = '1aa2b96bde0d64dbe43af90586a5cb0a'; // Hash de la Pregunta 3

$colors = [
    'azul', 'rojo', 'verde', 'amarillo', 'negro', 'blanco', 'gris', 
    'naranja', 'morado', 'rosado', 'rosa', 'violeta', 'cafe', 'marron',
    'blue', 'red', 'green', 'yellow', 'black', 'white'
];

echo "Testing colors against hash: $target\n\n";

foreach ($colors as $color) {
    $normalized = strtolower(trim($color));
    $hash = md5(md5($normalized));
    
    if ($hash === $target) {
        echo "SUCCESS! The color is: '$color'\n";
        exit(0);
    }
}

echo "No match found in common colors.\n";
