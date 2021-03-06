<?php
/**
 * @package go\DB
 * @subpackage Tests
 */

namespace go\Tests\DB\Helpers\Templater;

/**
 * coversDefaultClass go\DB\Helpers\Templater
 * @author Oleg Grigoriev <go.vasac@gmail.com>
 */
final class WhereTest extends Base
{
    /**
     * {@inheritdoc}
     */
    public function providerTemplater()
    {
        return [
            'plain' => [
                'WHERE ?w',
                [['x' => 1]],
                'WHERE `x`=1',
            ],
            'escape' => [
                'WHERE ?w',
                [['x' => 1, 'y' => 'qu"ot']],
                'WHERE `x`=1 AND `y`="qu\"ot"',
            ],
            'list' => [
                'WHERE ?where',
                [['x' => null, 'y' => [1, '2', 3]]],
                'WHERE `x` IS NULL AND `y` IN (1,"2",3)',
            ],
            'empty_list' => [
                'WHERE ?where',
                [['x' => null, 'y' => []]],
                'WHERE `x` IS NULL AND 1=0',
            ],
            'not_null' => [
                'WHERE ?where',
                [['one' => true, 'y' => '5', 'z' => 6]],
                'WHERE `one` IS NOT NULL AND `y`="5" AND `z`=6',
            ],
            'true' => [
                'WHERE ?w',
                [true],
                'WHERE 1=1',
            ],
            'empty' => [
                'WHERE ?w',
                [[]],
                'WHERE 1=1',
            ],
            'false' => [
                'WHERE ?w',
                [false],
                'WHERE 1=0',
            ],
            'operation1' => [
                'WHERE ?w',
                [['x' => 1, 'y' => ['op' => '<>', 'value' => 'xx']]],
                'WHERE `x`=1 AND `y`<>"xx"',
            ],
            'operation2' => [
                'WHERE ?w',
                [['x' => 2, 'y' => ['op' => '<=', 'col' => 'x']]],
                'WHERE `x`=2 AND `y`<=`x`',
            ],
            'col_value' => [
                'WHERE ?w',
                [['x' => 2, 'y' => ['op' => '=', 'col' => 'x', 'value' => 3]]],
                'WHERE `x`=2 AND `y`=`x`+3',
            ],
            'col_minus' => [
                'WHERE ?w',
                [['x' => 2, 'y' => ['op' => '<>', 'col' => 'z', 'value' => -5]]],
                'WHERE `x`=2 AND `y`<>`z`-5',
            ],
            'col_extended' => [
                'WHERE ?w',
                [
                    [
                        'col' => [
                            'op' => '<',
                            'db' => 'd',
                            'table' => 't',
                            'col' => 'c',
                            'value' => 3,
                            'func' => 'FUNC',
                        ],
                    ]
                ],
                'WHERE `col`<FUNC(`d`.`p_t`.`c`)+3',
                'p_',
            ],
            'groups' => [
                'WHERE ?w',
                [
                    [
                        'group_or' => [
                            'sep' => 'OR',
                            'group' => [
                                'x' => 1,
                                'and' => [
                                    'group' => [
                                        'x' => 5,
                                        'y' => [
                                            'op' => '>',
                                            'table' => 't',
                                            'col' => 'col',
                                            'value' => -4,
                                        ],
                                    ],
                                ],
                                'y' => [1, 2],
                            ],
                        ],
                        'x' => 10,
                    ],
                ],
                'WHERE (`x`=1 OR (`x`=5 AND `y`>`p_t`.`col`-4) OR `y` IN (1,2)) AND `x`=10',
                'p_',
            ],
            'withNull' => [
                'WHERE ?w',
                [
                    [
                        'x' => [1, 2],
                        'y' => [1, 2, null, 3],
                    ],
                ],
                'WHERE `x` IN (1,2) AND (`y` IN (1,2,3) OR `y` IS NULL)',
            ],
        ];
    }
}
