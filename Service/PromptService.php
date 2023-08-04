<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Service;

class PromptService
{
    public function metaTitle(): string
    {
        return 'create for me a plain text which will be used as a meta title for my cms page, only content, without any special characters or html tags. Without quotes. Use minimum 10 tokens.
          I sell safes and locks this will be the content of that page. Please do provide me an answer';
    }

    public function metaDescription(): string
    {
        return 'create for me an string with meta tile for my cms page';
    }

    public function contentHeading(): string
    {
        return 'create for me content heading for my cms page';
    }

    public function metaKeywords(): string
    {
        return 'create for me meta keywords for my cms page';
    }

    public function urlKey(): string
    {
        return 'create for me an string with meta tile for my cms page';
    }

    public function content(): string
    {
        return 'create for me content for my cms page';
    }
}
