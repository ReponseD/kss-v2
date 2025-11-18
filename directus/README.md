# Directus Backend for KSS Website

This is the Directus backend for Kagarama Secondary School CMS.

## Quick Start

### Local Development

1. Install dependencies:
```bash
npm install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Edit `.env` with your configuration

4. Initialize Directus:
```bash
npm run bootstrap
```

5. Start Directus:
```bash
npm start
```

Directus will be available at `http://localhost:8055`

### Using Docker

```bash
docker-compose up -d
```

## Collections

The following collections are configured:
- **posts** - News articles and blog posts
- **events** - Announcements and events
- **media** - Videos and media content

## API Access

API endpoint: `http://localhost:8055/items/{collection}`

Example: `http://localhost:8055/items/posts`

