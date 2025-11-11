# KSS Updates System - User Guide

## Table of Contents
1. [Getting Started](#getting-started)
2. [Admin Panel Overview](#admin-panel-overview)
3. [Creating Content](#creating-content)
4. [Managing Gallery](#managing-gallery)
5. [Frontend Features](#frontend-features)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)

---

## Getting Started

### Initial Setup

1. **Database Setup**
   - Import the database schema from `database/schema.sql`
   - Update database credentials in `config/database.php`

2. **First Login**
   - Navigate to: `http://your-domain.com/admin/login.php`
   - Default credentials:
     - Username: `admin`
     - Password: `admin123`
   - **⚠️ IMPORTANT**: Change password immediately after first login!

3. **Access the Admin Panel**
   - After login, you'll see the dashboard with statistics
   - Use the sidebar menu to navigate different sections

---

## Admin Panel Overview

### Dashboard
The dashboard provides:
- **Statistics**: Quick overview of published content counts
- **Quick Actions**: Buttons to quickly create new content
- **Recent Content**: List of recently created/updated items

### Navigation Menu
- **Dashboard**: Overview and statistics
- **News**: Manage news articles
- **Blogs**: Manage blog posts
- **Announcements**: Manage announcements
- **Gallery**: Upload and manage images
- **Categories**: Organize content by categories
- **Users**: Manage team members (Admin only)

---

## Creating Content

### Creating News Articles

1. **Navigate to News**
   - Click "News" in the sidebar
   - Click "Create News" button

2. **Fill in the Form**
   - **Title**: Enter a clear, descriptive title
   - **Excerpt**: Brief summary (appears in listings)
   - **Content**: Full article content (supports HTML)
   - **Featured Image**: Upload or select an image
   - **Category**: Select appropriate category
   - **Status**: 
     - `Draft`: Save for later editing
     - `Published`: Make visible on website
   - **Featured**: Check to highlight on homepage
   - **Tags**: Add relevant tags (comma-separated)

3. **Save**
   - Click "Save Draft" to save without publishing
   - Click "Publish" to make it live immediately

### Creating Blog Posts

1. **Navigate to Blogs**
   - Click "Blogs" in the sidebar
   - Click "Create Blog" button

2. **Fill in Details**
   - Similar to news articles
   - Use a more personal, engaging tone
   - Include relevant images

3. **Publish**
   - Save as draft or publish immediately

### Creating Announcements

1. **Navigate to Announcements**
   - Click "Announcements" in the sidebar
   - Click "Create Announcement" button

2. **Key Information**
   - **Title**: Clear, attention-grabbing headline
   - **Content**: Important information for students/parents
   - **Urgency**: Use "Featured" for urgent announcements
   - **Category**: Select appropriate category

3. **Publish**
   - Announcements are time-sensitive, publish immediately when ready

### Editing Content

1. **Find the Content**
   - Navigate to the appropriate section (News/Blogs/Announcements)
   - Find the item in the list
   - Click the "Edit" button (pencil icon)

2. **Make Changes**
   - Update any fields as needed
   - Change status if needed (Draft ↔ Published)

3. **Save Changes**
   - Click "Update" to save changes

### Deleting Content

1. **Find the Content**
   - Navigate to the content list
   - Click "Delete" button (trash icon)

2. **Confirm**
   - Confirm deletion in the popup
   - **⚠️ Warning**: This action cannot be undone!

---

## Managing Gallery

### Uploading Images

1. **Navigate to Gallery**
   - Click "Gallery" in the sidebar
   - Click "Upload Image" button

2. **Upload Form**
   - **Choose File**: Select image from your computer
   - **Title**: Descriptive title for the image
   - **Description**: Optional description
   - **Alt Text**: Important for accessibility and SEO
   - **Category**: Select gallery category
   - **Featured**: Check to highlight on homepage
   - **Display Order**: Set order (lower numbers appear first)

3. **Supported Formats**
   - JPEG (.jpg, .jpeg)
   - PNG (.png)
   - GIF (.gif)
   - WebP (.webp)
   - Maximum file size: 10MB

4. **Best Practices**
   - Use descriptive titles
   - Add alt text for accessibility
   - Organize by categories
   - Use high-quality images
   - Optimize file size before uploading

### Organizing Gallery

1. **Categories**
   - Use categories to group related images
   - Examples: Events, Sports, Academic, General

2. **Display Order**
   - Set display order to control image sequence
   - Lower numbers appear first

3. **Featured Images**
   - Mark important images as "Featured"
   - These appear prominently on the website

### Editing Gallery Items

1. **Find the Image**
   - Go to Gallery section
   - Find the image in the grid
   - Click "Edit" button

2. **Update Information**
   - Change title, description, alt text
   - Update category
   - Change display order
   - Toggle featured status

3. **Save Changes**

### Deleting Images

1. **Find the Image**
   - Go to Gallery section
   - Click "Delete" button

2. **Confirm Deletion**
   - **⚠️ Warning**: This permanently removes the image file!

---

## Frontend Features

### Updates Page (`Updates.html`)

**For Visitors:**
- View all updates in one place
- Filter by type: All, News, Blogs, Announcements
- Click on any item to read full content
- Responsive design works on all devices

**Features:**
- **Tabs**: Switch between content types
- **Cards**: Each item displayed as a card with:
  - Featured image
  - Title
  - Excerpt
  - Author and date
  - "Read More" button

### Gallery Page (`Gallery.html`)

**For Visitors:**
- Browse all uploaded images
- Filter by category
- Click images to view in lightbox
- Responsive grid layout

**Features:**
- **Category Filter**: Buttons to filter by category
- **Lightbox**: Click image to view full size
- **Hover Effects**: Interactive image previews

### Navigation

**Updates Dropdown Menu:**
- **All Updates**: View everything
- **News**: Only news articles
- **Blogs**: Only blog posts
- **Announcements**: Only announcements
- **Gallery**: Image gallery

---

## Best Practices

### Content Creation

1. **Writing Tips**
   - Use clear, concise language
   - Break content into paragraphs
   - Use headings for structure
   - Include relevant images
   - Proofread before publishing

2. **SEO Best Practices**
   - Use descriptive titles
   - Write compelling excerpts
   - Add relevant tags
   - Use proper categories
   - Include alt text for images

3. **Image Guidelines**
   - Recommended size: 1200x800px for featured images
   - File size: Keep under 500KB when possible
   - Format: Use JPEG for photos, PNG for graphics
   - Quality: Use high-quality images
   - Alt text: Always add descriptive alt text

4. **Content Organization**
   - Use appropriate categories
   - Add relevant tags
   - Keep content up-to-date
   - Archive old content when needed

### Workflow Recommendations

1. **Draft First**
   - Always create as draft first
   - Review and edit before publishing
   - Get approval if needed

2. **Regular Updates**
   - Post news regularly
   - Update gallery frequently
   - Keep announcements current

3. **Quality Control**
   - Proofread all content
   - Check images before publishing
   - Verify links work
   - Test on mobile devices

---

## Troubleshooting

### Common Issues

**Can't Login**
- Verify username and password
- Check if account is active
- Contact admin if locked out

**Images Won't Upload**
- Check file size (max 10MB)
- Verify file format (JPEG, PNG, GIF, WebP)
- Check uploads folder permissions
- Try a different browser

**Content Not Appearing**
- Check if status is "Published"
- Verify category is correct
- Clear browser cache
- Check if content is featured

**Gallery Not Loading**
- Check API connection
- Verify database connection
- Check browser console for errors
- Ensure images are uploaded correctly

### Getting Help

1. **Check Logs**
   - Review activity log in admin panel
   - Check server error logs

2. **Contact Support**
   - Document the issue
   - Include screenshots if possible
   - Note what you were trying to do

---

## Quick Reference

### Keyboard Shortcuts
- **Ctrl+S**: Save (in content editor)
- **Ctrl+P**: Preview (if available)

### Status Types
- **Draft**: Not visible to public
- **Published**: Visible on website
- **Archived**: Hidden but kept in database

### User Roles
- **Admin**: Full access, can manage users
- **Editor**: Can edit all content
- **Author**: Can create and edit own content

### File Limits
- **Image Upload**: 10MB maximum
- **Supported Formats**: JPEG, PNG, GIF, WebP

---

## Tips for Success

1. **Consistency**
   - Post regularly
   - Use consistent formatting
   - Maintain brand voice

2. **Engagement**
   - Use compelling headlines
   - Include relevant images
   - Write engaging content

3. **Organization**
   - Use categories effectively
   - Tag content properly
   - Keep gallery organized

4. **Quality**
   - Proofread everything
   - Use high-quality images
   - Ensure accuracy

---

## Need More Help?

- Review the setup guide: `README_SETUP.md`
- Check the code comments in API files
- Contact the development team
- Refer to PHP/MySQL documentation

---

**Last Updated**: 2025
**Version**: 1.0

