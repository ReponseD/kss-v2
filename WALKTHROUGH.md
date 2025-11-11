# Visual Walkthrough - KSS Updates System

## Step-by-Step Guide with Screenshots

### Part 1: First Login

#### Step 1: Access Admin Panel
1. Open your browser
2. Navigate to: `http://your-domain.com/admin/login.php`
3. You'll see the login page with KSS branding

#### Step 2: Login
1. Enter username: `admin`
2. Enter password: `admin123`
3. Click "Login" button
4. You'll be redirected to the dashboard

#### Step 3: Change Password (IMPORTANT!)
1. After first login, immediately change your password
2. Go to Users section (if available)
3. Or use SQL to update password hash

---

### Part 2: Creating Your First News Article

#### Step 1: Navigate to News
1. In the sidebar, click "News"
2. You'll see the news management page

#### Step 2: Create New Article
1. Click the "Create New" button (top right)
2. The content form will appear

#### Step 3: Fill in the Form
1. **Title**: Enter "Welcome to KSS Updates System"
2. **Excerpt**: Write a brief summary
3. **Content**: Use the rich text editor to write your article
   - Format text (bold, italic, headings)
   - Add lists
   - Insert links
   - Add images
4. **Category**: Select "School News"
5. **Status**: Choose "Published" to make it live
6. **Featured Image**: Add image URL (or upload separately)
7. **Tags**: Add relevant tags like "welcome, updates, news"

#### Step 4: Save
1. Click "Save" button
2. Article is now published and visible on the website!

---

### Part 3: Uploading Gallery Images

#### Step 1: Go to Gallery
1. Click "Gallery" in the sidebar
2. You'll see the gallery management page

#### Step 2: Upload Image
1. Click "Upload Image" button
2. Upload form appears

#### Step 3: Fill Upload Form
1. **Choose File**: Click and select an image from your computer
2. **Title**: Enter "KSS Main Building"
3. **Description**: Add description (optional)
4. **Alt Text**: Enter "Kagarama Secondary School main building entrance"
5. **Category**: Select "General Gallery"
6. **Featured**: Check if you want it featured
7. **Display Order**: Enter 1 (to show first)

#### Step 4: Upload
1. Click "Upload" button
2. Image is processed and added to gallery
3. It will appear on the Gallery page immediately

---

### Part 4: Viewing Content on Frontend

#### Step 1: Visit Updates Page
1. Navigate to: `http://your-domain.com/Updates.html`
2. You'll see tabs: All, News, Blogs, Announcements

#### Step 2: Browse Content
1. Click "News" tab to see only news articles
2. Each article appears as a card with:
   - Featured image
   - Title
   - Excerpt
   - Author and date
   - "Read More" button

#### Step 3: View Gallery
1. Navigate to: `http://your-domain.com/Gallery.html`
2. You'll see all uploaded images in a grid
3. Use category filters at the top
4. Click any image to view in lightbox

---

## Common Workflows

### Daily Workflow

**Morning:**
```
1. Login to admin panel
2. Check dashboard statistics
3. Review any drafts
4. Publish scheduled content
```

**Creating Content:**
```
1. Click appropriate section (News/Blogs/Announcements)
2. Click "Create New"
3. Fill in form
4. Save as draft or publish
```

**Managing Gallery:**
```
1. Click "Gallery"
2. Upload new images
3. Organize by categories
4. Set display order
```

### Weekly Tasks

**Content Review:**
- Review all published content
- Update outdated information
- Archive old content
- Check for broken links

**Gallery Maintenance:**
- Organize images into categories
- Remove duplicate images
- Update image descriptions
- Set featured images

---

## Tips & Tricks

### Content Creation
- **Use Templates**: Create templates for common content types
- **Save Drafts**: Always save as draft first, then publish
- **Preview**: Check how content looks before publishing
- **SEO**: Use descriptive titles and excerpts

### Image Management
- **Batch Upload**: Upload multiple images at once
- **Naming**: Use descriptive filenames
- **Organization**: Use categories consistently
- **Optimization**: Compress images before uploading

### Time-Saving
- **Quick Actions**: Use dashboard quick action buttons
- **Keyboard Shortcuts**: Use Ctrl+S to save
- **Templates**: Reuse content structures
- **Categories**: Set up categories in advance

---

## Troubleshooting Common Issues

### Issue: Can't see published content
**Solution:**
1. Check content status is "Published"
2. Clear browser cache
3. Verify category is correct
4. Check if content is featured

### Issue: Images not uploading
**Solution:**
1. Check file size (max 10MB)
2. Verify file format (JPEG, PNG, GIF, WebP)
3. Check uploads folder permissions
4. Try different browser

### Issue: Content editor not working
**Solution:**
1. Check JavaScript is enabled
2. Clear browser cache
3. Try different browser
4. Check browser console for errors

---

## Best Practices Checklist

Before Publishing:
- [ ] Title is clear and descriptive
- [ ] Content is proofread
- [ ] Images have alt text
- [ ] Category is selected
- [ ] Tags are added
- [ ] Featured image is set
- [ ] Status is correct
- [ ] Links work properly

Before Uploading Images:
- [ ] File size is reasonable
- [ ] Image is high quality
- [ ] Title is descriptive
- [ ] Alt text is added
- [ ] Category is selected
- [ ] Description is added

---

## Quick Reference Card

### Admin URLs
- Login: `/admin/login.php`
- Dashboard: `/admin/dashboard.php`
- News: `/admin/content.php?type=news`
- Blogs: `/admin/content.php?type=blog`
- Announcements: `/admin/content.php?type=announcement`
- Gallery: `/admin/gallery.php`

### Frontend URLs
- Updates: `/Updates.html`
- Gallery: `/Gallery.html`
- News Only: `/Updates.html?type=news`
- Blogs Only: `/Updates.html?type=blog`

### Status Types
- **Draft**: Not visible to public
- **Published**: Visible on website
- **Archived**: Hidden but kept

### File Limits
- Max upload: 10MB
- Formats: JPEG, PNG, GIF, WebP

---

**Remember**: Always save drafts before publishing, and proofread everything!

