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
 * Validator for Tanzania subdivision code.
 *
 * ISO 3166-1 alpha-2: TZ
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class TzSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        '01', // Arusha
        '02', // Dar-es-Salaam
        '03', // Dodoma
        '04', // Iringa
        '05', // Kagera
        '06', // Kaskazini Pemba
        '07', // Kaskazini Unguja
        '08', // Kigoma
        '09', // Kilimanjaro
        '10', // Kusini Pemba
        '11', // Kusini Unguja
        '12', // Lindi
        '13', // Mara
        '14', // Mbeya
        '15', // Mjini Magharibi
        '16', // Morogoro
        '17', // Mtwara
        '18', // Mwanza
        '19', // Pwani
        '20', // Rukwa
        '21', // Ruvuma
        '22', // Shinyanga
        '23', // Singida
        '24', // Tabora
        '25', // Tanga
        '26', // Manyara
    ];

    public $compareIdentical = true;
}
