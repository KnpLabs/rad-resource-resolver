<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in('spec')
    ->in('src')
;

PedroTroller\CS\Fixer\Contrib\SingleCommentExpandedFixer::setExpandedComments([
    '@var',
]);

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        'align_double_arrow',
        'align_equals',
        'concat_with_spaces',
        'line_break_between_statements',
        'logical_not_operators_with_spaces',
        'newline_after_open_tag',
        'no_empty_comment',
        'no_useless_return',
        'ordered_use',
        'php_unit_construct',
        'php_unit_strict',
        'phpdoc_order',
        'phpspec',
        'short_array_syntax',
        'single_comment_expanded',
        'strict_param',
    ])
    ->addCustomFixer(new PedroTroller\CS\Fixer\Contrib\PhpspecFixer())
    ->addCustomFixer(new PedroTroller\CS\Fixer\Contrib\LineBreakBetweenStatementsFixer())
    ->addCustomFixer(new PedroTroller\CS\Fixer\Contrib\SingleCommentExpandedFixer())
    ->finder($finder)
;
