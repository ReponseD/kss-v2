# Directus Collections Setup Guide

After installing Directus, you need to create these collections manually in the admin panel, or use the API to create them.

## Collection: posts

**Fields:**
- `id` (uuid, primary key) - Auto
- `title` (string, required) - Article title
- `slug` (string, unique) - URL-friendly identifier
- `author` (string, default: "Media Club") - Author name
- `content` (text, required) - Article content (Markdown supported)
- `excerpt` (text) - Short summary
- `featured_image` (file, image) - Featured image
- `category` (string, dropdown) - Options: News, Blog, Announcement
- `draft` (boolean, default: false) - Draft status
- `featured` (boolean, default: false) - Featured post
- `date_created` (timestamp) - Auto
- `date_updated` (timestamp) - Auto
- `date_published` (timestamp) - Publication date

**Display Template:** `{{title}}`

## Collection: events

**Fields:**
- `id` (uuid, primary key) - Auto
- `title` (string, required) - Event title
- `slug` (string, unique) - URL-friendly identifier
- `description` (text, required) - Event description
- `event_date` (timestamp, required) - Event start date
- `end_date` (timestamp) - Event end date (optional)
- `location` (string) - Event location
- `image` (file, image) - Event image
- `event_type` (string, dropdown) - Options: Announcement, Event, Meeting, Workshop, Sports, Academic
- `featured` (boolean, default: false) - Featured event
- `date_created` (timestamp) - Auto
- `date_updated` (timestamp) - Auto

**Display Template:** `{{title}}`

## Collection: media

**Fields:**
- `id` (uuid, primary key) - Auto
- `title` (string, required) - Media title
- `slug` (string, unique) - URL-friendly identifier
- `description` (text) - Media description
- `video_url` (string, required) - YouTube/Vimeo URL
- `video_type` (string, dropdown) - Options: YouTube, Vimeo
- `thumbnail` (file, image) - Thumbnail image
- `featured` (boolean, default: false) - Featured media
- `date_created` (timestamp) - Auto
- `date_updated` (timestamp) - Auto

**Display Template:** `{{title}}`

## Permissions

Set up permissions for:
- **Public Role**: Read-only access to all collections
- **Admin Role**: Full access to all collections

