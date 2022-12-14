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
 * Validator for Liberia subdivision code.
 *
 * ISO 3166-1 alpha-2: LR
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class LrSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        'BG', // Bong
        'BM', // Bomi
        'CM', // Grand Cape Mount
        'GB', // Grand Bassa
        'GG', // Grand Gedeh
        'GK', // Grand Kru
        'LO', // Lofa
        'MG', // Margibi
        'MO', // Montserrado
        'MY', // Maryland
        'NI', // Nimba
        'RI', // Rivercess
        'SI', // Sinoe
    ];

    public $compareIdentical = true;
}
