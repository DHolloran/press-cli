<?php
namespace PressCLI\WPCLI;

use PressCLI\WPCLI\CLI;

trait Post
{
    /**
     * Creates the database.
     */
    public static function postDeleteDefault()
    {
        $post_ids = [1, 2];
        foreach ($post_ids as $postId) {
            self::postDelete($postId);
        }
    }

    /**
     * Deletes a post by id..
     */
    public static function postDelete($postId)
    {
        CLI::execCommand('post', ['delete', $postId], ['force' => '']);
    }
}
