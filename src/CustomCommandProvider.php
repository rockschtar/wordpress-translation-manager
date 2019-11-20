<?php


namespace Rockschtar\WordPressTranslationManager;


use Composer\Plugin\Capability\CommandProvider;
use Rockschtar\WordPressTranslationManager\Commands\ForceUpdateCommand;

class CustomCommandProvider implements CommandProvider {

    /**
     * Retrieves an array of commands
     *
     * @return \Composer\Command\BaseCommand[]
     */
    public function getCommands() {

        return [new ForceUpdateCommand()];
    }
}