<?php
namespace KindlingCLI\Option;

use RuntimeException;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;

class Validator
{
    /**
     * Validates the configuration against the rules and requests/verifies items.
     *
     * @param  array           $config
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return array
     */
    public static function validate($config, QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        foreach (self::rules() as $ruleKey => $rule) {
            $value = self::getValue($config, $ruleKey);
            $value = self::askQuestion($value, $rule, $helper, $input, $output);
            $config = self::setValue($config, $value, $ruleKey);
        }

        return $config;
    }

    /**
     * Sets a configuration value from validation.
     *
     * @param array  $config
     * @param string $value
     * @param string $ruleKey
     */
    protected static function setValue($config, $value, $ruleKey)
    {
        // For now all validation is 2 levels deep so this should be good for now.
        $keys = explode(':', $ruleKey);
        $config[ $keys[0] ][ $keys[1] ] = $value;

        return $config;
    }

    /**
     * Asks a question of the user.
     *
     * @param  string          $value
     * @param  array           $rule
     * @param  QuestionHelper  $helper
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     *
     * @return string
     */
    protected static function askQuestion($value, $rule, QuestionHelper $helper, InputInterface $input, OutputInterface $output)
    {
        $message = $rule['question'];
        $verify = isset($rule['verify']) ? (boolean) $rule['verify'] : false;

        // We should only ask for things when they are empty or requested to verify.
        if (!$verify and $value) {
            return $value;
        }

        // Ask the question.
        $questionValue = $value ? " (<info>{$value}</info>)" : '';
        $question = new Question("{$message}{$questionValue}: ", $value);
        $question->setValidator(function ($answer) use ($message) {
            if (!$answer) {
                 throw new RuntimeException("{$message} is required!");
            }

            return $answer;
        });

        return $helper->ask($input, $output, $question);
    }

    /**
     * Get value from configuration.
     *
     * @param  array  $config
     * @param  string $ruleKey
     *
     * @return string
     */
    protected static function getValue($config, $ruleKey)
    {
        $value = '';
        foreach (explode(':', $ruleKey) as $configKey) {
            $config = $config[ $configKey ];
        }

        if (is_array($config)) {
            return '';
        }

        return (string) $config;
    }

    /**
     * Validation required rules.
     *
     * @return array
     */
    protected static function rules()
    {
        return [
            'database:user' => [
                'question' => 'Database User',
                'verify' => true,
            ],
            'database:password' => [
                'question' => 'Database Password',
            ],
            'database:prefix' => [
                'question' => 'Database Prefix',
            ],
            'database:name' => [
                'question' => 'Database Name',
                'verify' => true,
            ],
            'database:host' => [
                'question' => 'Database Host',
            ],
            'user:name' => [
                'question' => 'User Login',
                'verify' => true,
            ],
            'user:email' => [
                'question' => 'Email',
                'verify' => true,
            ],
            'user:password' => [
                'question' => 'Password',
                'verify' => true,
            ],
            'site:title' => [
                'question' => 'Site Title',
            ],
            'site:url' => [
                'question' => 'Site URL',
                'verify' => true,
            ],
        ];
    }
}
