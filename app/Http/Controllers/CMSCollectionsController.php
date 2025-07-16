<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Yaml\Yaml;
use League\CommonMark\CommonMarkConverter;

class CMSCollectionsController extends Controller
{
    /**
     * Get all items from a specific collection
     */
    public function index(string $collection): JsonResponse
    {
        $collectionPath = base_path("content/collections/{$collection}");
        $items = [];

        if (!is_dir($collectionPath)) {
            return response()->json(['error' => "Collection '{$collection}' not found"], 404);
        }

        $files = glob($collectionPath . '/*.md');

        foreach ($files as $file) {
            $item = $this->parseMarkdownFile($file);
            if ($item) {
                $items[] = $item;
            }
        }

        // Sort items based on tree ordering if available
        $items = $this->sortItemsByTreeOrder($collection, $items);

        return response()->json([
            'success' => true,
            'collection' => $collection,
            'count' => count($items),
            'items' => $items
        ]);
    }

    /**
     * Get a specific item by ID or slug from a collection
     */
    public function show(string $collection, string $identifier): JsonResponse
    {
        $collectionPath = base_path("content/collections/{$collection}");

        if (!is_dir($collectionPath)) {
            return response()->json(['error' => "Collection '{$collection}' not found"], 404);
        }

        $files = glob($collectionPath . '/*.md');

        foreach ($files as $file) {
            $item = $this->parseMarkdownFile($file);
            if ($item && (
                $item['id'] === $identifier ||
                (isset($item['slug']) && $item['slug'] === $identifier) ||
                $item['filename'] === $identifier
            )) {
                return response()->json([
                    'success' => true,
                    'collection' => $collection,
                    'item' => $item
                ]);
            }
        }

        return response()->json(['error' => 'Item not found'], 404);
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

                    // Convert markdown to HTML
                    $converter = new CommonMarkConverter();
                    $data['content_html'] = $converter->convert($body)->getContent();
                }

                // Convert specific markdown fields to HTML
                $this->convertMarkdownFields($data);

                // Add filename for reference
                $data['filename'] = basename($filePath, '.md');

                // Process image URLs to include storage prefix
                $this->processImageUrls($data);

                return $data;
            } catch (\Exception $e) {
                // If YAML parsing fails, skip this file
                return null;
            }
        }

        return null;
    }

    /**
     * Process various image fields and add storage URL prefix
     */
    private function processImageUrls(array &$data): void
    {
        // Common image field names to process
        $imageFields = ['logo', 'picture', 'image', 'images', 'photo', 'photos'];

        foreach ($imageFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = array_map(function($imageFile) {
                    return asset('storage/' . $imageFile);
                }, $data[$field]);
            }
        }
    }

    /**
     * Convert specific markdown fields to HTML
     */
    private function convertMarkdownFields(array &$data): void
    {
        $converter = new CommonMarkConverter();

        // Fields that should be converted from markdown to HTML
        $markdownFields = ['content', 'financial_report', 'old_business', 'new_business', 'announcements'];

        foreach ($markdownFields as $field) {
            if (isset($data[$field]) && is_string($data[$field]) && !empty($data[$field])) {
                // Convert markdown to HTML and store it
                $data[$field] = $converter->convert($data[$field])->getContent();
            }
        }

        // Handle the 'present' field specially - convert line breaks to HTML
        if (isset($data['present']) && is_string($data['present']) && !empty($data['present'])) {
            // Split by double line breaks to create paragraphs, then convert single line breaks to <br>
            $present = $data['present'];
            $paragraphs = explode("\n\n", $present);
            $htmlParagraphs = array_map(function($paragraph) {
                $paragraph = trim($paragraph);
                if (empty($paragraph)) return '';
                // Convert single line breaks to <br> tags
                $paragraph = nl2br($paragraph);
                return "<p>{$paragraph}</p>";
            }, $paragraphs);

            $data['present'] = implode("\n", array_filter($htmlParagraphs));
        }
    }

    /**
     * Sort items based on tree ordering if available
     */
    private function sortItemsByTreeOrder(string $collection, array $items): array
    {
        $treePath = base_path("content/trees/collections/{$collection}.yaml");

        if (!file_exists($treePath)) {
            return $items; // Return unsorted if no tree file exists
        }

        try {
            $treeContent = file_get_contents($treePath);
            $treeData = Yaml::parse($treeContent);

            if (!isset($treeData['tree']) || !is_array($treeData['tree'])) {
                return $items; // Return unsorted if tree structure is invalid
            }

            // Extract the ordered entry IDs from the tree
            $orderedIds = [];
            foreach ($treeData['tree'] as $treeItem) {
                if (isset($treeItem['entry'])) {
                    $orderedIds[] = $treeItem['entry'];
                }
            }

            // Create a lookup array for items by ID
            $itemsById = [];
            foreach ($items as $item) {
                if (isset($item['id'])) {
                    $itemsById[$item['id']] = $item;
                }
            }

            // Sort items according to the tree order
            $sortedItems = [];
            foreach ($orderedIds as $id) {
                if (isset($itemsById[$id])) {
                    $sortedItems[] = $itemsById[$id];
                    unset($itemsById[$id]); // Remove from lookup to avoid duplicates
                }
            }

            // Add any remaining items that weren't in the tree order (at the end)
            foreach ($itemsById as $item) {
                $sortedItems[] = $item;
            }

            return $sortedItems;

        } catch (\Exception $e) {
            // If YAML parsing fails, return unsorted items
            return $items;
        }
    }
}
