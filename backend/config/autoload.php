<?php

spl_autoload_register(function (string $className) : void {
    // Depuis le dossier "config", on remonte d'un cran ("../") pour atteindre les autres dossiers
    $directories = [
        __DIR__ . '/../controllers/',
        __DIR__ . '/../managers/',
        __DIR__ . '/../models/',
        __DIR__ . '/../services/',
    ];

    // On parcourt chaque dossier pour chercher le fichier de la classe
    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';

        // Si le fichier existe, on l'inclut automatiquement
        if (file_exists($file)) {
            require_once $file;
            return; // Fichier trouvé, on arrête la recherche
        }
    }
});