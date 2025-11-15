/**
 * Build Script for KSS Website
 * Converts Markdown files from content/ folder to JSON for frontend consumption
 */

const fs = require('fs');
const path = require('path');

// Try to load gray-matter, fallback to simple parser if not available
let matter;
try {
  matter = require('gray-matter');
} catch (e) {
  console.warn('gray-matter not found, using simple parser');
  matter = null;
}

// Directories
const contentDir = path.join(__dirname, '../content');
const outputDir = path.join(__dirname, '../assets/data');
const outputFile = path.join(outputDir, 'content.json');

// Ensure output directory exists
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
  console.log('✅ Created output directory:', outputDir);
}

/**
 * Simple frontmatter parser (fallback if gray-matter not available)
 */
function parseFrontmatter(content) {
  const frontmatterRegex = /^---\s*\n([\s\S]*?)\n---\s*\n([\s\S]*)$/;
  const match = content.match(frontmatterRegex);
  
  if (match) {
    const frontmatterText = match[1];
    const body = match[2];
    const data = {};
    
    // Parse YAML-like frontmatter (simple key: value pairs)
    frontmatterText.split('\n').forEach(line => {
      const colonIndex = line.indexOf(':');
      if (colonIndex > 0) {
        const key = line.substring(0, colonIndex).trim();
        let value = line.substring(colonIndex + 1).trim();
        
        // Remove quotes if present
        if ((value.startsWith('"') && value.endsWith('"')) || 
            (value.startsWith("'") && value.endsWith("'"))) {
          value = value.slice(1, -1);
        }
        
        // Parse boolean
        if (value === 'true') value = true;
        if (value === 'false') value = false;
        
        // Parse date
        if (key === 'date' && value) {
          try {
            value = new Date(value).toISOString();
          } catch (e) {
            // Keep original value
          }
        }
        
        data[key] = value;
      }
    });
    
    return { data, content: body };
  }
  
  return { data: {}, content };
}

/**
 * Read markdown files from a directory
 */
function readMarkdownFiles(dir, type) {
  if (!fs.existsSync(dir)) {
    console.warn(`⚠️  Directory not found: ${dir}`);
    return [];
  }

  const files = fs.readdirSync(dir);
  const items = [];

  files.forEach(file => {
    if (file.endsWith('.md')) {
      const filePath = path.join(dir, file);
      const fileContent = fs.readFileSync(filePath, 'utf-8');
      
      // Parse frontmatter
      let parsed;
      if (matter) {
        parsed = matter(fileContent);
      } else {
        parsed = parseFrontmatter(fileContent);
      }
      
      const { data, content } = parsed;
      
      // Generate slug from filename
      const slug = file.replace('.md', '');
      
      // Determine category based on type
      let category = data.category || type;
      if (type === 'posts') {
        category = data.category || 'News';
      } else if (type === 'events') {
        category = 'Announcement';
      } else if (type === 'media') {
        category = 'Media';
      }
      
      // Create content item
      const item = {
        id: slug,
        slug: slug,
        type: type,
        title: data.title || 'Untitled',
        author: data.author || 'Media Club',
        date: data.date ? new Date(data.date).toISOString() : new Date().toISOString(),
        created_at: data.date ? new Date(data.date).toISOString() : new Date().toISOString(),
        published_at: data.date ? new Date(data.date).toISOString() : new Date().toISOString(),
        featured_image: data.featured_image || null,
        excerpt: data.excerpt || content.substring(0, 150).replace(/\n/g, ' ').trim() + '...',
        content: content.trim(),
        draft: data.draft === true || data.draft === 'true',
        featured: data.featured === true || data.featured === 'true',
        category: category,
        content_type: category
      };
      
      // Add type-specific fields
      if (type === 'events') {
        item.event_date = data.date ? new Date(data.date).toISOString() : item.date;
        item.end_date = data.end_date ? new Date(data.end_date).toISOString() : null;
        item.location = data.location || null;
        item.event_type = data.event_type || 'Event';
      }
      
      if (type === 'media') {
        item.video_url = data.video_url || null;
        item.video_type = data.video_type || 'YouTube';
        item.thumbnail = data.thumbnail || data.featured_image || null;
      }
      
      items.push(item);
    }
  });

  // Sort by date (newest first)
  return items.sort((a, b) => new Date(b.date) - new Date(a.date));
}

// Main build function
function buildContent() {
  console.log('🚀 Building content from Markdown files...\n');
  
  // Read all content types
  const postsDir = path.join(contentDir, 'posts');
  const eventsDir = path.join(contentDir, 'events');
  const mediaDir = path.join(contentDir, 'media');
  
  const posts = readMarkdownFiles(postsDir, 'posts');
  const events = readMarkdownFiles(eventsDir, 'events');
  const media = readMarkdownFiles(mediaDir, 'media');
  
  // Filter out drafts and combine
  const allContent = {
    posts: posts.filter(p => !p.draft),
    events: events.filter(e => !e.draft),
    media: media.filter(m => !m.draft),
    all: [...posts, ...events, ...media]
      .filter(item => !item.draft)
      .sort((a, b) => new Date(b.date) - new Date(a.date))
  };
  
  // Write JSON file
  fs.writeFileSync(outputFile, JSON.stringify(allContent, null, 2));
  
  // Print summary
  console.log('✅ Content build complete!');
  console.log(`   📄 Total items: ${allContent.all.length}`);
  console.log(`   📰 Posts: ${allContent.posts.length}`);
  console.log(`   📅 Events: ${allContent.events.length}`);
  console.log(`   🎥 Media: ${allContent.media.length}`);
  console.log(`   📦 Output: ${outputFile}\n`);
  
  return allContent;
}

// Run build if called directly
if (require.main === module) {
  try {
    buildContent();
    process.exit(0);
  } catch (error) {
    console.error('❌ Build failed:', error);
    process.exit(1);
  }
}

module.exports = { buildContent };

