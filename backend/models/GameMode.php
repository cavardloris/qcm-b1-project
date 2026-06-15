<?php
enum Mode: string {
    case theme  = 'theme';
    case sudden_death = 'sudden_death';
}

    //  on crée un enum pour forcer le choix entre les deux modes et supprimer toutes
    // possibilités de mettre des mauvais nom de mode dans l'objet score