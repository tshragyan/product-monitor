<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class OlxHelper
{
    public static function isRightDomain($url): bool
    {
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['host'];

        return str_contains($domain, '.olx.');
    }

    public static function getJsonScriptFromContent($url): array|null
    {
        $html = null;

        try {
            $client = new Client();
            $response = $client->request('GET', $url);
            $html = $response->getBody()->getContents();
        } catch (\Throwable $e) {
            die('a');
            return null;
        }

        if ($html) {
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $scripts = $dom->getElementsByTagName('script');
            $jsonContent = null;

            foreach ($scripts as $script) {
                if ($script->getAttribute('type') === 'application/ld+json') {
                    $jsonContent = $script->nodeValue;
                    break;
                }
            }

            if (!$jsonContent) {
                return null;
            }

            return json_decode($jsonContent, true);
        }

        return null;
    }
}
