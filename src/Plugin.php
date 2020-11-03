<?php

namespace Rockschtar\WordPressTranslationManager;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Rockschtar\WordPressTranslationManager\Manager\TranslationManager;

class Plugin implements PluginInterface, EventSubscriberInterface, Capable {

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var TranslationManager
     */
    private $translationManager;

    public static function getSubscribedEvents(): array {
        return [
            PackageEvents::POST_PACKAGE_UPDATE => 'update',
            PackageEvents::POST_PACKAGE_INSTALL => 'install',
            PackageEvents::POST_PACKAGE_UNINSTALL => 'remove'
        ];
    }

    /**
     * Apply plugin modifications to Composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io): void {
        $this->translationManager = new TranslationManager(PluginConfig::getConfig($composer->getPackage()), $io);
        $this->composer = $composer;
        $this->io = $io;
    }

    public function getCapabilities() {
        return array(
            CommandProvider::class => CustomCommandProvider::class,
        );
    }

    public function update(PackageEvent $packageEvent): void {
        if (!method_exists($packageEvent->getOperation(), 'getTargetPackage')) {
            return;
        }

        $this->translationManager->update($packageEvent->getOperation()->getTargetPackage());
    }

    public function install(PackageEvent $packageEvent): void {
        if (!method_exists($packageEvent->getOperation(), 'getPackage')) {
            return;
        }

        $this->translationManager->update($packageEvent->getOperation()->getPackage());
    }

    public function remove(PackageEvent $packageEvent) {

        if (!method_exists($packageEvent->getOperation(), 'getPackage')) {
            return;
        }

        $this->translationManager->remove($packageEvent->getOperation()->getPackage());
    }


    public function deactivate(Composer $composer, IOInterface $io)
    {
        // TODO: Implement deactivate() method.
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // TODO: Implement uninstall() method.
    }
}