<?php

declare(strict_types=1);

namespace Eclipse\AiCmsPage\Service;

use Eclipse\AiCore\Client\Connection;
use Eclipse\AiCore\Model\ConfigProvider;
use Exception;
use OpenAI\Responses\Completions\CreateResponse;

class AiService
{
    /**
     * @param ConfigProvider $configProvider
     * @param Connection $connection
     * @param PromptService $promptService
     */
    public function __construct(
        private readonly ConfigProvider $configProvider,
        private readonly Connection     $connection,
        private readonly PromptService  $promptService
    )
    {
    }

    /**
     * @param string $area
     * @param string $pageLanguage
     * @return string
     * @throws Exception
     */
    public function generate(string $area, string $pageLanguage): string
    {
        $client = $this->connection->create();
        $result = $client->chat()->create([
            'model' => $this->getModel(),
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $this->getPrompt($area) . $this->getLanguage($pageLanguage)
                ],
            ],
            'max_tokens' => 2
        ]);
        return $result->choices[0]->message->content;
    }

    /**
     * @param $area
     * @return string
     */
    private function getPrompt($area): string
    {
        return match ($area) {
            'content heading' => $this->promptService->contentHeading(),
            'content' => $this->promptService->content(),
            'url key' => $this->promptService->urlKey(),
            'meta title' => $this->promptService->metaTitle(),
            'meta keywords' => $this->promptService->metaKeywords(),
            'meta description' => $this->promptService->metaDescription()
        };
    }

    /**
     * @param $pageLanguage
     * @return string
     */
    private function getLanguage($pageLanguage): string
    {
        return " in '.$pageLanguage.' language'";
    }

    /**
     * @return string
     */
    private function getModel(): string
    {
        return $this->configProvider->getEclipseAiEngine();
    }
}
