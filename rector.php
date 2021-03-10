<?php
/**
 * Rector init file
 *
 * @package easy-watermark
 */

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\DowngradeSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function ( ContainerConfigurator $container_configurator ) : void {
	$parameters = $container_configurator->parameters();
	$parameters->set(Option::SETS, [
		DowngradeSetList::PHP_80,
		DowngradeSetList::PHP_74,
		DowngradeSetList::PHP_73,
		DowngradeSetList::PHP_72,
		DowngradeSetList::PHP_71,
		DowngradeSetList::PHP_70,
	]);
};
