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
 * Validator for Mauritania subdivision code.
 *
 * ISO 3166-1 alpha-2: MR
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class MrSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        '01', // Hodh ech Chargui
        '02', // Hodh el Charbi
        '03', // Assaba
        '04', // Gorgol
        '05', // Brakna
        '06', // Trarza
        '07', // Adrar
        '08', // Dakhlet Nouadhibou
        '09', // Tagant
        '10', // Guidimaka
        '11', // Tiris Zemmour
        '12', // Inchiri
        'NKC', // Nouakchott
    ];

    public $compareIdentical = true;
}
