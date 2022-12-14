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
 * Validator for Sudan subdivision code.
 *
 * ISO 3166-1 alpha-2: SD
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class SdSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        'DC', // Zalingei
        'DE', // Sharq Dārfūr
        'DN', // Shamāl Dārfūr
        'DS', // Janūb Dārfūr
        'DW', // Gharb Dārfūr
        'GD', // Al Qaḑārif
        'GZ', // Al Jazīrah
        'KA', // Kassalā
        'KH', // Al Kharţūm
        'KN', // Shamāl Kurdufān
        'KS', // Janūb Kurdufān
        'NB', // An Nīl al Azraq
        'NO', // Ash Shamālīyah
        'NR', // An Nīl
        'NW', // An Nīl al Abyaḑ
        'RS', // Al Baḩr al Aḩmar
        'SI', // Sinnār
    ];

    public $compareIdentical = true;
}
