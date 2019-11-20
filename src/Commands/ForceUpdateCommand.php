<?php


namespace Rockschtar\WordPressTranslationManager\Commands;


use Composer\Command\BaseCommand;
use Rockschtar\WordPressTranslationManager\Manager\TranslationManager;
use Rockschtar\WordPressTranslationManager\PluginConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ForceUpdateCommand extends BaseCommand {
    protected function configure() {
        $this->setName('update-wordpress-translations');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $packages = $this->getComposer()
                         ->getRepositoryManager()
                         ->getLocalRepository()
                         ->getPackages();

        $translationManager = new TranslationManager(PluginConfig::getConfig($this->getComposer()->getPackage()), $this->getIO());
        $translationManager->updatePackages($packages);
    }
}