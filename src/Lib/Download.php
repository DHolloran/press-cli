<?php
namespace PressCLI\Lib;

use GuzzleHttp\Client;

class Download
{
    /**
     * Downloads a file.
     *
     * @param  string $url       The URL to download the file.
     * @param  string $directory The directory to download the file to.
     * @param  string $type      The file type (zip|tar) to save the downloaded file as.
     *
     * @return string            The path to the downloaded file on success and an empty string on failure.    .
     */
    public static function execute($url, $directory, $type)
    {
        // Download the file.
        $client = new Client();
        $response = $client->get($url);
        if (200 !== $response->getStatusCode()) {
            return '';
        }

        // Store the temporary file.
        $tempFile = self::makeTempName($directory, $type);
        file_put_contents($tempFile, $response->getBody());

        return $tempFile;
    }

    /**
     * Makes the downloaded files temporary name.
     *
     * @param  string $directory The directory the file is downloaded to.
     * @param  string $type      The file type (zip|tar) to save the downloaded file as.
     *
     * @return string            The temporary file name.
     */
    protected static function makeTempName($directory, $type)
    {
        return $directory . '/' . md5(time().uniqid()) . ".{$type}";
    }
}
