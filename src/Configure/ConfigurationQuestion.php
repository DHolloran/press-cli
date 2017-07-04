<?php

namespace PressCLI\Configure;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ConfigurationQuestion
{
    /**
     * Question helper.
     *
     * @var QuestionHelper
     */
    protected $question;

    /**
     * Console input interface
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Console output interface.
     *
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * The configuration
     *
     * @var Collection
     */
    protected $configuration;

    /**
     * @param QuestionHelper $question
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Illuminate\Support\Collection $configuration
     *
     * @codingStandardsIgnoreStart
     */
    public function __construct(QuestionHelper $question, InputInterface $input, OutputInterface $output, $configuration)
    {
        // @codingStandardsIgnoreEnd
        $this->question = $question;
        $this->input = $input;
        $this->output = $output;
        $this->configuration = $configuration;
    }

    /**
     * Gets missing configuration.
     *
     * @return Illuminate\Support\Collection
     */
    public function ask($configuration)
    {
        // Database
        $this->askAboutSection('database');

        // User
        $this->askAboutSection('user');

        // Site
        $this->askAboutSection('site');

        // Theme
        $this->askAboutThemeInstall();

        return $this->configuration;
    }

    protected function askAboutThemeInstall()
    {
        if ($this->askForConfirmation('Install custom theme')) {
            $this->askAboutSection('theme');
        }
    }

    /**
     * Asks a about a section of the configuration.
     *
     * @param  string $section
     * @param  null|string $messageLabel
     * @param  array  $skipKeys
     *
     * @return Illuminate\Support\Collection
     */
    protected function askAboutSection($section, $messageLabel = null, $skipKeys = [])
    {
        $messageLabel = ucwords($section);
        $answers = $this->configuration->get($section, collect([]))
                    ->map(function ($item, $key) use ($messageLabel) {
                        return $this->askForInput("{$messageLabel} {$key}", $item);
                    });

        $this->configuration->put($section, $answers);
    }

    /**
     * Asks for a confirmation from the user.
     *
     * @param  string  $message
     * @param  boolean $default
     *
     * @return boolean
     */
    protected function askForConfirmation($message, $default = true)
    {
        $message = trim($message);
        $defaultLabel = $default ? 'Y/n' : 'y/N';
        $message = "<info>{$message}? ($defaultLabel)</info>";
        $question = new ConfirmationQuestion($message, $default);

        return $this->question->ask($this->input, $this->output, $question);
    }

    /**
     * Asks for user input.
     *
     * @param  string $message
     * @param  string $default
     * @param  boolean $isRequired
     * @param  boolean $isError
     *
     * @return mixed
     */
    public function askForInput($message, $default = '', $isRequired = true, $isError = false)
    {
        $answer = $this->question->ask(
            $this->input,
            $this->output,
            $question = new Question($this->buildQuestion($message, $default, $isRequired, $isError), $default)
        );

        if ($isRequired and !$answer) {
            $answer = $this->askForInput($message, $default, $isRequired, $isError = true);
        }

        return $answer;
    }

    /**
     * Builds up the question.
     *
     * @param  string  $message
     * @param  string  $default
     * @param  boolean $isRequired
     * @param  boolean $isError
     *
     * @return string
     */
    protected function buildQuestion($message, $default = '', $isRequired = true, $isError = false)
    {
        $question = trim($message) . '?';
        $question = $isRequired ? "{$question}*" : $question;
        $question = $default ?  "{$question} ({$default})" : $question;
        $question = "{$question}: ";
        $question = $isError ? "<error>{$question}</error>" : "<info>{$question}</info>";

        return $question;
    }
}
