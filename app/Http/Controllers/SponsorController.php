<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Yaml\Yaml;

class SponsorController extends Controller
{
    /**
     * Get all sponsors from markdown files
     */
    public function index(): JsonResponse
    {
        $sponsorsPath = base_path('content/collections/sponsors');
        $sponsors = [];

        if (!is_dir($sponsorsPath)) {
            return response()->json(['error' => 'Sponsors directory not found'], 404);
        }

        $files = glob($sponsorsPath . '/*.md');

        foreach ($files as $file) {
            $sponsor = $this->parseMarkdownFile($file);
            if ($sponsor) {
                $sponsors[] = $sponsor;
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($sponsors),
            'sponsors' => $sponsors
        ]);
    }

    /**
     * Get a specific sponsor by ID
     */
    public function show(string $id): JsonResponse
    {
        $sponsorsPath = base_path('content/collections/sponsors');
        $files = glob($sponsorsPath . '/*.md');

        foreach ($files as $file) {
            $sponsor = $this->parseMarkdownFile($file);
            if ($sponsor && $sponsor['id'] === $id) {
                return response()->json([
                    'success' => true,
                    'sponsor' => $sponsor
                ]);
            }
        }

        return response()->json(['error' => 'Sponsor not found'], 404);
    }

    /**
     * Parse a markdown file and extract frontmatter data
     */
    private function parseMarkdownFile(string $filePath): ?array
    {
        $content = file_get_contents($filePath);

        if (!$content) {
            return null;
        }

        // Extract frontmatter between --- delimiters
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            $frontmatter = $matches[1];
            $body = trim($matches[2]);

            try {
                // Parse YAML frontmatter
                $data = Yaml::parse($frontmatter);

                // Add the markdown body if it exists
                if (!empty($body)) {
                    $data['content'] = $body;
                }

                // Add filename for reference
                $data['filename'] = basename($filePath, '.md');

                // Process logo URLs to include storage prefix
                if (isset($data['logo']) && is_array($data['logo'])) {
                    $data['logo'] = array_map(function($logoFile) {
                        return asset('storage/' . $logoFile);
                    }, $data['logo']);
                }

                return $data;
            } catch (\Exception $e) {
                // If YAML parsing fails, skip this file
                return null;
            }
        }

        return null;
    }
}
