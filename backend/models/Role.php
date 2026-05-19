<?php
enum Role: string {
    case ADMIN = 'ADMIN';
    case USER = 'USER';
}

//  on crée un enum pour forcer le choix entre les deux roles et supprimer toutes
// possibilités de mettre des mauvais role dans l'objet user