# CMS Collections API Documentation

This API provides endpoints to retrieve content from any collection in the `content/collections` directory as JSON.

## Endpoints

### GET /api/collections/{collection}

Returns all items from a specific collection.

**Parameters:**
- `collection` (string): The name of the collection (e.g., "sponsors", "getinvolved")

**Response:**
```json
{
  "success": true,
  "collection": "sponsors",
  "count": 2,
  "items": [
    {
      "id": "690fa988-e989-4eaf-b495-04ec81a7c175",
      "blueprint": "sponsor",
      "title": "Kayak Point Disc Golf Resort",
      "sponsor_name": "Kayak Point Disc Golf Resort",
      "web_site": "https://discgolfresort.com/",
      "driving_directions": "https://www.google.com/maps/dir//7703%20150th%20Pl%20NW,%20Stanwood,%20WA%2098292",
      "updated_by": 1,
      "updated_at": 1752452998,
      "logo": ["http://127.0.0.1:8000/storage/kayak.png"],
      "filename": "kayak-point-disc-golf-resort"
    }
  ]
}
```

### GET /api/collections/{collection}/{id}

Returns a specific item by ID from a collection.

**Parameters:**
- `collection` (string): The name of the collection
- `id` (string): The unique ID of the item

**Response (Success):**
```json
{
  "success": true,
  "collection": "getinvolved",
  "item": {
    "id": "c612e581-6210-42c5-b345-828362e1297e",
    "blueprint": "getinvolved",
    "title": "Telecommunications",
    "picture": ["http://127.0.0.1:8000/storage/telecomm.jpeg"],
    "updated_by": 1,
    "updated_at": 1752465315,
    "content": "- Email Campaigns\n- Website Editing & Maintenance\n- Calendar updating",
    "content_html": "<ul>\n<li>Email Campaigns</li>\n<li>Website Editing &amp; Maintenance</li>\n<li>Calendar updating</li>\n</ul>\n",
    "filename": "telecommunications"
  }
}
```

**Response (Not Found):**
```json
{
  "error": "Item not found"
}
```

**Response (Collection Not Found):**
```json
{
  "error": "Collection 'nonexistent' not found"
}
```

## Backward Compatibility

The following routes are maintained for backward compatibility:
- `GET /api/sponsors` → redirects to `/api/collections/sponsors`
- `GET /api/sponsors/{id}` → redirects to `/api/collections/sponsors/{id}`

## Supported Collections

Currently available collections:
- `sponsors` - Sponsor information
- `getinvolved` - Get involved opportunities

## Features

- ✅ Generic collection support
- ✅ Parses YAML frontmatter from markdown files
- ✅ **Markdown to HTML conversion** - `content` field is parsed and returned as both raw markdown and HTML
- ✅ Includes markdown content if present (`content` and `content_html` fields)
- ✅ Automatically processes image URLs with storage prefix
- ✅ Supports multiple image field names (logo, picture, image, images, photo, photos)
- ✅ Adds filename for reference
- ✅ Handles errors gracefully
- ✅ Returns structured JSON responses

### Content Processing

When a markdown file has content below the frontmatter, it's processed as follows:
- `content` - Raw markdown content
- `content_html` - Parsed HTML from the markdown

**Supported Markdown Features:**
- Headers (`# ## ###`)
- **Bold** and *italic* formatting
- Lists (ordered and unordered)
- Links `[text](url)`
- Code blocks with syntax highlighting
- Blockquotes
- And all other CommonMark features

## Usage Examples

```bash
# Get all sponsors
curl -X GET "http://your-domain.com/api/collections/sponsors" -H "Accept: application/json"

# Get all get-involved items
curl -X GET "http://your-domain.com/api/collections/getinvolved" -H "Accept: application/json"

# Get specific sponsor
curl -X GET "http://your-domain.com/api/collections/sponsors/690fa988-e989-4eaf-b495-04ec81a7c175" -H "Accept: application/json"

# Get specific get-involved item
curl -X GET "http://your-domain.com/api/collections/getinvolved/c612e581-6210-42c5-b345-828362e1297e" -H "Accept: application/json"
```
