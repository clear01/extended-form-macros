<?php

use Instante\ExtendedFormMacros\PairAttributes;
use InstanteTests\ExtendedFormMacros\Latte\MacroTester;
use Nette\Forms\Controls\TextInput;

require __DIR__ . '/../../bootstrap.php';
require __DIR__ . '/MacroTester.inc';

$tester = new MacroTester('{form theForm}{pair foo}{/form}');

$tester->getForm()->addText('foo', 'Foo field');

$tester->getMockRenderer()->shouldReceive('renderBegin');
$tester->getMockRenderer()->shouldReceive('renderEnd');

$tester->getMockRenderer()->shouldReceive('renderPair')->with(
    Mockery::type(TextInput::class),
    Mockery::type(PairAttributes::class)
)->once();
$tester->render();
