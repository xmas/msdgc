# Sponsors API Documentation

This API provides endpoints to retrieve sponsor data from markdown files in the `content/collections/sponsors` directory.

## Endpoints

### GET /api/sponsors

Returns all sponsors as JSON.

**Response:**
```json
{
  "success": true,
  "count": 1,
  "sponsors": [
    {
      "id": "690fa988-e989-4eaf-b495-04ec81a7c175",
      "blueprint": "sponsor",
      "title": "Kayak Point Disc Golf Resort",
      "sponsor_name": "Kayak Point Disc Golf Resort",
      "web_site": "https://discgolfresort.com/",
      "driving_directions": "https://www.google.com/maps/dir//7703%20150th%20Pl%20NW,%20Stanwood,%20WA%2098292",
      "updated_by": 1,
      "updated_at": 1752452998,
      "logo": ["ban_1.png"],
      "filename": "kayak-point-disc-golf-resort"
    }
  ]
}
```

### GET /api/sponsors/{id}

Returns a specific sponsor by ID.

**Parameters:**
- `id` (string): The unique ID of the sponsor

**Response (Success):**
```json
{
  "success": true,
  "sponsor": {
    "id": "690fa988-e989-4eaf-b495-04ec81a7c175",
    "blueprint": "sponsor",
    "title": "Kayak Point Disc Golf Resort",
    "sponsor_name": "Kayak Point Disc Golf Resort",
    "web_site": "https://discgolfresort.com/",
    "driving_directions": "https://www.google.com/maps/dir//7703%20150th%20Pl%20NW,%20Stanwood,%20WA%2098292",
    "updated_by": 1,
    "updated_at": 1752452998,
    "logo": ["ban_1.png"],
    "filename": "kayak-point-disc-golf-resort"
  }
}
```

**Response (Not Found):**
```json
{
  "error": "Sponsor not found"
}
```

## Features

- Parses YAML frontmatter from markdown files
- Includes markdown content if present
- Adds filename for reference
- Handles errors gracefully
- Returns structured JSON responses

## Usage Examples

```bash
# Get all sponsors
curl -X GET "http://your-domain.com/api/sponsors" -H "Accept: application/json"

# Get specific sponsor
curl -X GET "http://your-domain.com/api/sponsors/690fa988-e989-4eaf-b495-04ec81a7c175" -H "Accept: application/json"
```
