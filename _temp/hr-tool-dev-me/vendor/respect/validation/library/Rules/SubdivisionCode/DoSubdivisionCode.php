<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules\SubdivisionCode;

use Respect\Validation\Rules\AbstractSearcher;

/**
 * Validator for Dominican Republic subdivision code.
 *
 * ISO 3166-1 alpha-2: DO
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class DoSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        '01', // Distrito Nacional (Santo Domingo)
        '02', // Azua
        '03', // Bahoruco
        '04', // Barahona
        '05', // Dajabón
        '06', // Duarte
        '07', // La Estrelleta [Elías Piña]
        '08', // El Seybo [El Seibo]
        '09', // Espaillat
        '10', // Independencia
        '11', // La Altagracia
        '12', // La Romana
        '13', // La Vega
        '14', // María Trinidad Sánchez
        '15', // Monte Cristi
        '16', // Pedernales
        '17', // Peravia
        '18', // Puerto Plata
        '19', // Salcedo
        '20', // Samaná
        '21', // San Cristóbal
        '22', // San Juan
        '23', // San Pedro de Macorís
        '24', // Sánchez Ramírez
        '25', // Santiago
        '26', // Santiago Rodríguez
        '27', // Valverde
        '28', // Monseñor Nouel
        '29', // Monte Plata
        '30', // Hato Mayor
    ];

    public $compareIdentical = true;
}
