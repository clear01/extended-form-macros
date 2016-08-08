<?php

namespace InstanteTests\ExtendedFormMacros\DI;

use Instante\ExtendedFormMacros\DI\RendererExtension;

use Instante\ExtendedFormMacros\Latte\FormRenderingDispatcher;
use Instante\ExtendedFormMacros\Latte\FormMacros;
use Nette\Configurator;
use Nette\DI\Compiler;

require_once __DIR__ . '/../../bootstrap.php';

// ::register()
$mockConfigurator = mock(Configurator::class);
RendererExtension::register($mockConfigurator);
$mockCompiler = \Mockery::mock(Compiler::class);

/** @noinspection PhpMethodParametersCountMismatchInspection */
$mockCompiler->shouldReceive('addExtension')
    ->with(\Mockery::type('string'), \Mockery::type(RendererExtension::class))
    ->once();
$mockConfigurator->onCompile[0]($mockConfigurator, $mockCompiler);


// ::loadConfiguration()
$ext = new RendererExtension;
$ext->setCompiler($mockCompiler, 'foo');

$mockCompiler->shouldReceive('getContainerBuilder->getDefinition->addSetup')->withArgs(function ($arg) {
    return strpos($arg, FormMacros::class) !== FALSE;
})->once()->andReturnSelf();
$mockCompiler->shouldReceive('getContainerBuilder->getDefinition->addSetup')->withArgs(function ($arg) {
    return strpos($arg, 'formRenderingDispatcher') !== FALSE;
})->once()->andReturnSelf();
$mockCompiler->shouldReceive('getContainerBuilder->addDefinition->setClass')->with(FormRenderingDispatcher::class)->once();
$ext->loadConfiguration();
