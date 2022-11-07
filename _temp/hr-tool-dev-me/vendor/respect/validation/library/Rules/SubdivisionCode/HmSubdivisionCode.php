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
 * Validator for Heard Island and McDonald Islands subdivision code.
 *
 * ISO 3166-1 alpha-2: HM
 *
 * @link https://salsa.debian.org/iso-codes-team/iso-codes
 */
class HmSubdivisionCode extends AbstractSearcher
{
    public $haystack = [
        'F', // Flat Island
        'H', // Heard Island
        'M', // McDonald Island
        'S', // Shag Island
    ];

    public $compareIdentical = true;
}
