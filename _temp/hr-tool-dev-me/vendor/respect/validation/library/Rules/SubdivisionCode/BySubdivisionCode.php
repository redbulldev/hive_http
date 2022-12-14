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
 * Validator for Belarus subdivision code.
 *
 * ISO 3166-1 alpha-2: BY
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class BySubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        'BR', // Brèsckaja voblasc'
        'HM', // Horad Minsk
        'HO', // Homel'skaja voblasc'
        'HR', // Hrodzenskaja voblasc'
        'MA', // Mahilëuskaja voblasc'
        'MI', // Minskaja voblasc'
        'VI', // Vicebskaja voblasc'
    ];

    public $compareIdentical = true;
}
