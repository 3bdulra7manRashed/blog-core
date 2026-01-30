<?php

namespace App\Support;

use App\Models\Post;

class PublishingIdentityResolver
{
    /**
     * Resolve the publishing identity for a post based on configuration.
     *
     * @param Post $post
     * @return \App\Models\User
     */
    public static function forPost(Post $post)
    {
        // 1. Determine Mode from DB
        $mode = setting('blog_identity_mode', 'single');

        // 2. Return Site Owner Identity
        if ($mode === 'single') {
            return SiteOwnerResolver::get();
        }

        // 3. Return Actual Author Identity (Multi-author mode)
        return $post->author;
    }
}
