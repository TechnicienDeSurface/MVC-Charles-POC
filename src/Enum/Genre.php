<?php

namespace App\Enum;

enum Genre: string
{
    case ELECTRO = 'Electro';
    case METAL = 'Metal';
    case ROCK = 'Rock';
    case ACTION = 'Action';
    case COMEDY = 'Comédie';
    case DRAMA = 'Drame';
    case HORROR = 'Horreur';
    case ROMANCE = 'Romance';
    case THRILLER = 'Thriller';
    case SCIENCE_FICTION = 'Science-Fiction';
    case DOCUMENTARY = 'Documentaire';
    case ANIMATION = 'Animation';
    case FANTASY = 'Fantastique';
}