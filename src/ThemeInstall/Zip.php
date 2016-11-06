<?php
namespace PressCLI\ThemeInstall;

use ZipArchive;
use PressCLI\Lib\Download;

class Zip
{
    /**
     * Downloads a theme and unzips it.
     *
     * @param  string $url       The URL to download the theme.
     * @param  string $directory The directory to download the theme to.
     * @param  string $name      The theme directory name.
     *
     * @return string            The path to the downloaded theme on success and an empty string on failure.    .
     */
    public static function execute($url, $directory, $name)
    {
        $directory = rtrim($directory, '/');
        $zip = Download::execute($url, $directory, 'zip');
        if (!$zip) {
            return '';
        }

        self::extract($zip, $directory, $name);

        return "{$directory}/{$name}";
    }

    /**
     * Rename the extracted zip file.
     *
     * @param  string $extracted The extracted zip file location.
     * @param  string $directory The extracted zip file directory.
     * @param  string $name      The name to rename the extracted zip file.
     */
    protected static function rename($extracted, $directory, $name)
    {
        if (file_exists($extracted)) {
            rename($extracted, "{$directory}/{$name}");
        }
    }

    /**
     * Deletes the downloaded zip.
     *
     * @param  string $zip The path to the zip file.
     */
    protected static function cleanUp($zip)
    {
        if (file_exists($zip)) {
            unlink($zip);
        }
    }

    /**
     * Extracts a zip file.
     *
     * @param  string $zip       The path to the zip file.
     * @param  string $directory The extraction directory.
     */
    public static function extract($zip, $directory, $name)
    {
        $archive = new ZipArchive;

        // Open and extract the zip to the specified directory.
        $archive->open($zip);
        $archive->extractTo($directory);

        // Rename the theme and close the archive.
        $extractedName = $archive->getNameIndex(0);
        self::rename("{$directory}/{$extractedName}", $directory, $name);
        $archive->close();

        // Remove the downloaded zip.
        self::cleanUp($zip);
    }
}
