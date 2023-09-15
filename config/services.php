<?php

declare(strict_types=1);

use App\Service\TelegramApiRequestSender;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container
        ->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/')->exclude(
        '../src/{DependencyInjection,Entity,Kernel.php}',
    );

    $services->set(TelegramApiRequestSender::class)
        ->arg('$url', '%env(TELEGRAM_URL)%');
};
