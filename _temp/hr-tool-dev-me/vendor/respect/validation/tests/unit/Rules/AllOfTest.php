<?php

/*
 * This file is part of Respect/Validation.
 *
 * (c) Alexandre Gomes Gaigalas <alexandre@gaigalas.net>
 *
 * For the full copyright and license information, please view the "LICENSE.md"
 * file that was distributed with this source code.
 */

namespace Respect\Validation\Rules;

use Respect\Validation\TestCase;

/**
 * @group  rule
 * @covers Respect\Validation\Rules\AllOf
 * @covers Respect\Validation\Exceptions\AllOfException
 */
class AllOfTest extends TestCase
{
    public function testRemoveRulesShouldRemoveAllRules()
    {
        $o = new AllOf(new IntVal(), new Positive());
        $o->removeRules();
        $this->assertEquals(0, count($o->getRules()));
    }

    public function testAddRulesUsingArrayOfRules()
    {
        $o = new AllOf();
        $o->addRules(
            [
                [$x = new IntVal(), new Positive()],
            ]
        );
        $this->assertTrue($o->hasRule($x));
        $this->assertTrue($o->hasRule('Positive'));
    }

    public function testAddRulesUsingSpecificationArray()
    {
        $o = new AllOf();
        $o->addRules(['Between' => [1, 2]]);
        $this->assertTrue($o->hasRule('Between'));
    }

    public function testValidationShouldWorkIfAllRulesReturnTrue()
    {
        $valid1 = new Callback(function () {
            return true;
        });
        $valid2 = new Callback(function () {
            return true;
        });
        $valid3 = new Callback(function () {
            return true;
        });
        $o = new AllOf($valid1, $valid2, $valid3);
        $this->assertTrue($o->__invoke('any'));
        $this->assertTrue($o->check('any'));
        $this->assertTrue($o->assert('any'));
        $this->assertTrue($o->__invoke(''));
        $this->assertTrue($o->check(''));
        $this->assertTrue($o->assert(''));
    }

    /**
     * @dataProvider providerStaticDummyRules
     * @expectedException Respect\Validation\Exceptions\AllOfException
     */
    public function testValidationAssertShouldFailIfAnyRuleFailsAndReturnAllExceptionsFailed($v1, $v2, $v3)
    {
        $o = new AllOf($v1, $v2, $v3);
        $this->assertFalse($o->__invoke('any'));
        $this->assertFalse($o->assert('any'));
    }

    /**
     * @dataProvider providerStaticDummyRules
     * @expectedException Respect\Validation\Exceptions\CallbackException
     */
    public function testValidationCheckShouldFailIfAnyRuleFailsAndThrowTheFirstExceptionOnly($v1, $v2, $v3)
    {
        $o = new AllOf($v1, $v2, $v3);
        $this->assertFalse($o->__invoke('any'));
        $this->assertFalse($o->check('any'));
    }

    /**
     * @dataProvider providerStaticDummyRules
     * @expectedException Respect\Validation\Exceptions\ValidationException
     */
    public function testValidationCheckShouldFailOnEmptyInput($v1, $v2, $v3)
    {
        $o = new AllOf($v1, $v2, $v3);
        $this->assertTrue($o->check(''));
    }

    /**
     * @dataProvider providerStaticDummyRules
     */
    public function testValidationShouldFailIfAnyRuleFails($v1, $v2, $v3)
    {
        $o = new AllOf($v1, $v2, $v3);
        $this->assertFalse($o->__invoke('any'));
    }

    public function providerStaticDummyRules()
    {
        $theInvalidOne = new Callback(function () {
            return false;
        });
        $valid1 = new Callback(function () {
            return true;
        });
        $valid2 = new Callback(function () {
            return true;
        });

        return [
            [$theInvalidOne, $valid1, $valid2],
            [$valid2, $valid1, $theInvalidOne],
            [$valid2, $theInvalidOne, $valid1],
            [$valid1, $valid2, $theInvalidOne],
            [$valid1, $theInvalidOne, $valid2],
        ];
    }
}
