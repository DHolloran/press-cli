<?php
namespace PressCLI\Exception;

class ConfigurationNotFound extends \Exception {
    protected $message = "Press CLI Configuration file not present!\nRun press init <project-name> to get started.";
}
