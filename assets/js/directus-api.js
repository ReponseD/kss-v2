/**
 * Directus API Integration for KSS Website
 * Replace this URL with your actual Directus instance URL
 */

const DIRECTUS_URL = 'https://your-directus-instance.onrender.com'; // UPDATE THIS!

/**
 * Get image URL from Directus file ID
 */
function getImageUrl(fileId, width = null, height = null) {
    if (!fileId) return 'https://placehold.co/400x200';
    
    let url = `${DIRECTUS_URL}/assets/${fileId}`;
    if (width || height) {
        const params = new URLSearchParams();
        if (width) params.append('width', width);
        if (height) params.append('height', height);
        params.append('fit', 'cover');
        url += '?' + params.toString();
    }
    return url;
}

/**
 * Get all posts (news/blog/announcement)
 */
async function getPosts(category = null) {
    try {
        const url = new URL(`${DIRECTUS_URL}/items/posts`);
        url.searchParams.append('filter[draft][_eq]', 'false');
        url.searchParams.append('sort[]', '-date_published');
        url.searchParams.append('fields[]', 'id,title,slug,author,excerpt,featured_image,category,date_published,content_type');
        
        if (category) {
            url.searchParams.append('filter[category][_eq]', category);
        }
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Failed to fetch posts');
        
        const data = await response.json();
        return data.data || [];
    } catch (error) {
        console.error('Error fetching posts:', error);
        return [];
    }
}

/**
 * Get a single post by slug
 */
async function getPostBySlug(slug) {
    try {
        const url = new URL(`${DIRECTUS_URL}/items/posts`);
        url.searchParams.append('filter[slug][_eq]', slug);
        url.searchParams.append('fields[]', '*');
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Failed to fetch post');
        
        const data = await response.json();
        return data.data && data.data.length > 0 ? data.data[0] : null;
    } catch (error) {
        console.error('Error fetching post:', error);
        return null;
    }
}

/**
 * Get all events
 */
async function getEvents() {
    try {
        const url = new URL(`${DIRECTUS_URL}/items/events`);
        url.searchParams.append('sort[]', '-event_date');
        url.searchParams.append('fields[]', 'id,title,slug,description,event_date,end_date,location,image,event_type');
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Failed to fetch events');
        
        const data = await response.json();
        return data.data || [];
    } catch (error) {
        console.error('Error fetching events:', error);
        return [];
    }
}

/**
 * Get all media items
 */
async function getMedia() {
    try {
        const url = new URL(`${DIRECTUS_URL}/items/media`);
        url.searchParams.append('sort[]', '-date_created');
        url.searchParams.append('fields[]', 'id,title,slug,description,video_url,video_type,thumbnail');
        
        const response = await fetch(url);
        if (!response.ok) throw new Error('Failed to fetch media');
        
        const data = await response.json();
        return data.data || [];
    } catch (error) {
        console.error('Error fetching media:', error);
        return [];
    }
}

/**
 * Get all content (posts + events) combined
 */
async function getAllContent() {
    try {
        const [posts, events] = await Promise.all([
            getPosts(),
            getEvents()
        ]);
        
        // Combine and sort by date
        const all = [...posts, ...events].map(item => {
            // Normalize date field
            const date = item.date_published || item.event_date || item.date_created;
            return { ...item, date, sortDate: new Date(date) };
        }).sort((a, b) => b.sortDate - a.sortDate);
        
        return all;
    } catch (error) {
        console.error('Error fetching all content:', error);
        return [];
    }
}

// Export for use in other scripts
if (typeof window !== 'undefined') {
    window.DirectusAPI = {
        DIRECTUS_URL,
        getPosts,
        getPostBySlug,
        getEvents,
        getMedia,
        getAllContent,
        getImageUrl
    };
}

