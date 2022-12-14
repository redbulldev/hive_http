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
 * Validator for Republic of the Congo subdivision code.
 *
 * ISO 3166-1 alpha-2: CG
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class CgSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        '11', // Bouenza
        '12', // Pool
        '13', // Sangha
        '14', // Plateaux
        '15', // Cuvette-Ouest
        '2', // Lékoumou
        '5', // Kouilou
        '7', // Likouala
        '8', // Cuvette
        '9', // Niari
        'BZV', // Brazzaville
    ];

    public $compareIdentical = true;
}
