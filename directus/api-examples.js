/**
 * Directus API Integration Examples
 * Use these examples to integrate Directus API with your frontend
 */

// Base URL - Update this with your Directus instance URL
const DIRECTUS_URL = 'https://your-directus-instance.onrender.com';

// ============================================
// PUBLIC API (No Authentication Required)
// ============================================

/**
 * Get all published posts
 */
async function getPosts(category = null) {
  const url = new URL(`${DIRECTUS_URL}/items/posts`);
  url.searchParams.append('filter[draft][_eq]', 'false');
  url.searchParams.append('sort[]', '-date_published');
  url.searchParams.append('fields[]', 'id,title,slug,author,excerpt,featured_image,category,date_published');
  
  if (category) {
    url.searchParams.append('filter[category][_eq]', category);
  }
  
  const response = await fetch(url);
  const data = await response.json();
  return data.data;
}

/**
 * Get a single post by slug
 */
async function getPostBySlug(slug) {
  const url = new URL(`${DIRECTUS_URL}/items/posts`);
  url.searchParams.append('filter[slug][_eq]', slug);
  url.searchParams.append('fields[]', '*');
  
  const response = await fetch(url);
  const data = await response.json();
  return data.data[0] || null;
}

/**
 * Get all events
 */
async function getEvents() {
  const url = new URL(`${DIRECTUS_URL}/items/events`);
  url.searchParams.append('sort[]', '-event_date');
  url.searchParams.append('fields[]', 'id,title,slug,description,event_date,end_date,location,image,event_type');
  
  const response = await fetch(url);
  const data = await response.json();
  return data.data;
}

/**
 * Get all media items
 */
async function getMedia() {
  const url = new URL(`${DIRECTUS_URL}/items/media`);
  url.searchParams.append('sort[]', '-date_created');
  url.searchParams.append('fields[]', 'id,title,slug,description,video_url,video_type,thumbnail');
  
  const response = await fetch(url);
  const data = await response.json();
  return data.data;
}

/**
 * Get featured content
 */
async function getFeaturedContent() {
  const url = new URL(`${DIRECTUS_URL}/items/posts`);
  url.searchParams.append('filter[featured][_eq]', 'true');
  url.searchParams.append('filter[draft][_eq]', 'false');
  url.searchParams.append('sort[]', '-date_published');
  url.searchParams.append('limit', '3');
  url.searchParams.append('fields[]', 'id,title,slug,excerpt,featured_image,date_published');
  
  const response = await fetch(url);
  const data = await response.json();
  return data.data;
}

// ============================================
// AUTHENTICATED API (For Admin Panel)
// ============================================

/**
 * Login to Directus
 */
async function login(email, password) {
  const response = await fetch(`${DIRECTUS_URL}/auth/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      email,
      password,
    }),
  });
  
  const data = await response.json();
  return data.data; // Contains access_token and refresh_token
}

/**
 * Create a new post (requires authentication)
 */
async function createPost(token, postData) {
  const response = await fetch(`${DIRECTUS_URL}/items/posts`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(postData),
  });
  
  const data = await response.json();
  return data.data;
}

/**
 * Update a post (requires authentication)
 */
async function updatePost(token, postId, postData) {
  const response = await fetch(`${DIRECTUS_URL}/items/posts/${postId}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(postData),
  });
  
  const data = await response.json();
  return data.data;
}

/**
 * Delete a post (requires authentication)
 */
async function deletePost(token, postId) {
  const response = await fetch(`${DIRECTUS_URL}/items/posts/${postId}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  });
  
  return response.ok;
}

// ============================================
// FILE/IMAGE URLS
// ============================================

/**
 * Get full URL for an image
 * Directus stores file IDs, use this to get the actual URL
 */
function getImageUrl(fileId) {
  if (!fileId) return null;
  return `${DIRECTUS_URL}/assets/${fileId}`;
}

/**
 * Get thumbnail URL for an image
 */
function getThumbnailUrl(fileId, width = 400, height = 300) {
  if (!fileId) return null;
  return `${DIRECTUS_URL}/assets/${fileId}?width=${width}&height=${height}&fit=cover`;
}

// ============================================
// EXPORT FOR USE IN FRONTEND
// ============================================

// For use in browser:
if (typeof window !== 'undefined') {
  window.DirectusAPI = {
    getPosts,
    getPostBySlug,
    getEvents,
    getMedia,
    getFeaturedContent,
    login,
    createPost,
    updatePost,
    deletePost,
    getImageUrl,
    getThumbnailUrl,
    DIRECTUS_URL,
  };
}

// For use in Node.js:
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    getPosts,
    getPostBySlug,
    getEvents,
    getMedia,
    getFeaturedContent,
    login,
    createPost,
    updatePost,
    deletePost,
    getImageUrl,
    getThumbnailUrl,
  };
}

