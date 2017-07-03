<?php

namespace PressCLI\Exception;

class CommandException extends \Exception
{
    /**
     * @param string|array $message
     * @param null|Throwable $previous
     */
    public function __construct($message, $previous = null)
    {
        $message = is_array($message) ? implode("\n", $message) : $message;

        parent::__construct($message, 1, $previous);
    }
}
