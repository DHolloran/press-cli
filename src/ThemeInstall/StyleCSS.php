<?php
namespace KindlingCLI\ThemeInstall;

use KindlingCLI\Option\Configuration;

class StyleCSS
{
    /**
     * Sets the style.css from the template.style.css.
     */
    public static function set()
    {
        $config = Configuration::get();
        $themePath = getcwd() . "/wp-content/themes/{$config['theme']['name']}";

        // Render template.
        $template = self::renderTemplate($themePath);

        // Write rendered template to style.css
        self::write($template, $themePath);

        // Delete template
        self::deleteTemplate($themePath);
    }

    /**
     * Renders the template from the configuration.
     *
     * @param  string $themePath The path to the theme.
     *
     * @return string            The rendered template on success and empty string on failure.
     */
    protected static function renderTemplate($themePath)
    {
        $template = self::getTemplate($themePath);
        if (!$template) {
            return '';
        }

        $config = Configuration::get();
        foreach ($config['theme']['style-css'] as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }

        return $template;
    }

    /**
     * Gets the template.
     *
     * @param  string $themePath The path to the theme.
     */
    protected static function getTemplate($themePath)
    {
        $templatePath = "{$themePath}/template.style.css";
        if (!file_exists($templatePath)) {
            return '';
        }

        return file_get_contents($templatePath);
    }

    /**
     * Deletes the template.
     *
     * @param  string $themePath The path to the theme.
     */
    protected static function deleteTemplate($themePath)
    {
        $templatePath = "{$themePath}/template.style.css";
        if (file_exists($templatePath)) {
            unlink($templatePath);
        }
    }

    /**
     * Writes the template to style.css.
     *
     * @param  string $template  Template content to write.
     * @param  string $themePath The path to the theme.
     */
    protected static function write($template, $themePath)
    {
        // If the template is empty then we are done here.
        if (!$template) {
            return;
        }

        file_put_contents("{$themePath}/style.css", $template);
    }
}
