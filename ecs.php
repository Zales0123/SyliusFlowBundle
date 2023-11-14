<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Comment\NoEmptyCommentFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToParamTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToPropertyTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToReturnTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use SlevomatCodingStandard\Sniffs\Commenting\ForbiddenAnnotationsSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\NullableTypeForNullDefaultValueSniff;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->import(__DIR__ . '/vendor/sylius-labs/coding-standard/ecs.php');

    $ecsConfig->paths([
        __DIR__ . '/Controller',
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Process',
        __DIR__ . '/Storage',
        __DIR__ . '/Validator',
        __DIR__ . '/SyliusFlowBundle.php',
    ]);

    $ecsConfig->rules([
        DeclareStrictTypesFixer::class,
        NoEmptyCommentFixer::class,
        NoEmptyPhpdocFixer::class,
        NoTrailingWhitespaceInCommentFixer::class,
        NoUnusedImportsFixer::class,
        NullableTypeForNullDefaultValueSniff::class,
        PhpdocToParamTypeFixer::class,
        PhpdocToPropertyTypeFixer::class,
        PhpdocToReturnTypeFixer::class,
        ReturnTypeDeclarationFixer::class,
        VoidReturnFixer::class,
    ]);

    $ecsConfig->ruleWithConfiguration(ForbiddenAnnotationsSniff::class, ['forbiddenAnnotations' => [
        '@api',
        '@category',
        '@copyright',
        '@created',
        '@license',
        '@package',
        '@since',
        '@subpackage',
        '@version',
    ]]);

    $ecsConfig->ruleWithConfiguration(PhpdocLineSpanFixer::class, [
        'property' => 'single',
        'const' => 'single',
        'method' => 'single',
    ]);

    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, [
        'remove_inheritdoc' => true,
    ]);
};
