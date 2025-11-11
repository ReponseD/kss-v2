# Netlify CMS Setup Guide for Kagarama Secondary School

## Overview

This guide will help you set up Netlify CMS for the Kagarama Secondary School website. Netlify CMS is a Git-based content management system that allows students and teachers to manage website content without touching code.

**Website:** https://www.kagaramasec.org/  
**CMS Admin:** https://www.kagaramasec.org/admin  
**Repository:** Your GitHub repository  
**Hosting:** Vercel

---

## Table of Contents

1. [Initial Setup](#initial-setup)
2. [Authentication Setup](#authentication-setup)
3. [Adding Users](#adding-users)
4. [Using the CMS](#using-the-cms)
5. [Content Collections](#content-collections)
6. [How It Works](#how-it-works)
7. [Troubleshooting](#troubleshooting)

---

## Initial Setup

### Step 1: Enable Netlify Identity & Git Gateway

Since you're using Vercel (not Netlify), you have two options:

#### Option A: Use Netlify for CMS Only (Recommended)

1. Create a free Netlify account at https://www.netlify.com
2. Add your GitHub repository to Netlify
3. Enable **Netlify Identity**:
   - Go to Site Settings → Identity
   - Click "Enable Identity"
   - Enable **Git Gateway** (allows Git operations)
4. Configure Git Gateway:
   - Go to Site Settings → Identity → Services → Git Gateway
   - Click "Enable Git Gateway"
5. Deploy settings:
   - Build command: Leave empty (or use a simple command)
   - Publish directory: `/` (root)
   - Since you're using Vercel for hosting, this Netlify site is just for CMS

#### Option B: Use GitHub OAuth (Alternative)

1. Update `admin/config.yml`:
   ```yaml
   backend:
     name: github
     repo: your-username/your-repo-name
     branch: main
   ```
2. Users will authenticate directly with GitHub
3. Requires GitHub OAuth app setup

### Step 2: Update Repository Settings

1. Ensure your repository is public (or configure OAuth for private repos)
2. Verify the branch name matches in `admin/config.yml` (default: `main`)

### Step 3: Test CMS Access

1. Visit https://www.kagaramasec.org/admin
2. You should see the Netlify Identity login screen
3. Create your first admin account

---

## Authentication Setup

### For Netlify Identity (Option A)

1. **Enable Identity:**
   - In Netlify dashboard: Site Settings → Identity → Enable Identity

2. **Enable Git Gateway:**
   - Site Settings → Identity → Services → Git Gateway → Enable

3. **Configure Registration:**
   - Site Settings → Identity → Registration
   - Choose "Invite only" (recommended for school)
   - Or "Open" if you want self-registration

4. **Set Up Roles (Optional):**
   - Site Settings → Identity → Roles
   - Add roles: `admin`, `editor`, `contributor`
   - Assign roles when inviting users

### For GitHub OAuth (Option B)

1. Create GitHub OAuth App:
   - GitHub → Settings → Developer settings → OAuth Apps
   - Create new OAuth App
   - Authorization callback URL: `https://api.netlify.com/auth/done`
   - Get Client ID and Client Secret

2. Add to Netlify:
   - Site Settings → Identity → Services → GitHub
   - Enter Client ID and Client Secret

---

## Adding Users

### Method 1: Invite Users (Recommended)

1. Go to Netlify Dashboard → Your Site → Identity
2. Click "Invite users"
3. Enter email address
4. Select role (if configured):
   - **Admin**: Full access to all content
   - **Editor**: Can create, edit, delete all content
   - **Contributor**: Can create content, requires approval (if editorial workflow enabled)

### Method 2: Self-Registration (If Enabled)

1. Users visit https://www.kagaramasec.org/admin
2. Click "Sign up"
3. Enter email and password
4. Verify email (if email verification enabled)
5. Default role: Contributor

### Method 3: GitHub OAuth

1. Users visit https://www.kagaramasec.org/admin
2. Click "Login with GitHub"
3. Authorize the application
4. Access granted based on repository permissions

---

## Using the CMS

### Accessing the CMS

1. Navigate to https://www.kagaramasec.org/admin
2. Log in with your credentials
3. You'll see the CMS dashboard with collections

### Creating Content

#### Creating a Post/Article

1. Click "Posts / Articles" in the sidebar
2. Click "New Post / Article"
3. Fill in the fields:
   - **Title**: Article title
   - **Author**: Your name or "Media Club"
   - **Publish Date**: When to publish
   - **Featured Image**: Upload or select image
   - **Body**: Write content using Markdown
   - **Category**: News, Blog, or Announcement
   - **Featured**: Check to feature on homepage
4. Click "Publish" (or "Save" for draft)

#### Creating Media/Video

1. Click "Media / Videos"
2. Click "New Media / Video"
3. Fill in:
   - **Title**: Video title
   - **Video URL**: YouTube or Vimeo link
   - **Video Type**: YouTube or Vimeo
   - **Thumbnail**: Optional custom thumbnail
   - **Description**: Video description
4. Click "Publish"

#### Creating Event/Announcement

1. Click "Announcements / Events"
2. Click "New Announcement / Event"
3. Fill in:
   - **Title**: Event name
   - **Event Date**: Start date and time
   - **End Date**: Optional end time
   - **Location**: Where the event takes place
   - **Description**: Full event details
   - **Image**: Event flyer or image
   - **Event Type**: Announcement, Event, Meeting, etc.
4. Click "Publish"

### Editing Content

1. Click on any collection (Posts, Media, Events)
2. Click on the item you want to edit
3. Make changes
4. Click "Save" or "Publish"

### Deleting Content

1. Open the item you want to delete
2. Click the "Delete" button (usually in the top right)
3. Confirm deletion

---

## Content Collections

### Posts / Articles (`content/posts/`)

- **Purpose**: News articles, blog posts, announcements
- **File Format**: Markdown (`.md`)
- **Naming**: `YYYY-MM-DD-slug.md`
- **Fields**:
  - Title, Author, Date
  - Featured Image
  - Body (Markdown)
  - Category, Featured status

### Media / Videos (`content/media/`)

- **Purpose**: Video content from YouTube/Vimeo
- **File Format**: Markdown (`.md`)
- **Naming**: `slug.md`
- **Fields**:
  - Title, Description
  - Video URL (YouTube/Vimeo)
  - Thumbnail Image
  - Video Type

### Announcements / Events (`content/events/`)

- **Purpose**: School events, announcements, meetings
- **File Format**: Markdown (`.md`)
- **Naming**: `YYYY-MM-DD-slug.md`
- **Fields**:
  - Title, Description
  - Event Date, End Date
  - Location
  - Image
  - Event Type

---

## How It Works

### Git-Based Content Management

1. **Content Storage**: All content is stored as Markdown files in the `content/` folder
2. **Git Commits**: Every change creates a Git commit in your repository
3. **Automatic Deployment**: Vercel detects the commit and rebuilds the site
4. **Live Updates**: Changes appear on the website after Vercel deployment

### Workflow

```
User edits in CMS
    ↓
CMS creates/updates Markdown file
    ↓
Git commit created in repository
    ↓
Vercel detects commit
    ↓
Vercel rebuilds site
    ↓
Changes go live on website
```

### File Structure

```
your-repo/
├── admin/
│   ├── index.html      # CMS interface
│   └── config.yml      # CMS configuration
├── content/
│   ├── posts/          # Articles
│   ├── media/          # Videos
│   └── events/         # Events/Announcements
└── assets/
    └── uploads/        # Uploaded images
```

---

## Troubleshooting

### CMS Not Loading

1. **Check admin/index.html exists**: Should be at `/admin/index.html`
2. **Check config.yml**: Verify syntax is correct
3. **Check browser console**: Look for JavaScript errors
4. **Verify Netlify Identity**: Ensure it's enabled in Netlify dashboard

### Can't Login

1. **Check Identity settings**: Ensure Identity is enabled
2. **Check Git Gateway**: Must be enabled for Git operations
3. **Try different browser**: Clear cache and cookies
4. **Check email verification**: May need to verify email first

### Changes Not Appearing

1. **Check Git commits**: Verify commits are being created
2. **Check Vercel deployment**: Ensure Vercel is connected to repo
3. **Check branch**: Ensure CMS is committing to correct branch
4. **Wait for deployment**: Vercel rebuild takes 1-2 minutes

### Images Not Uploading

1. **Check folder permissions**: `assets/uploads/` must exist
2. **Check file size**: Large files may fail
3. **Check Git LFS**: May need Git LFS for large files
4. **Check public_folder**: Should match `public_folder` in config.yml

### GitHub OAuth Issues

1. **Check OAuth app settings**: Callback URL must be correct
2. **Check repository access**: User must have write access
3. **Check branch protection**: May need to disable for CMS branch

---

## Best Practices

### For Students

1. **Always preview before publishing**: Use preview mode
2. **Write clear titles**: Make titles descriptive
3. **Use images**: Add featured images to posts
4. **Check spelling**: Proofread before publishing
5. **Save drafts**: Use draft mode for work in progress

### For Teachers/Admins

1. **Review content**: Check student submissions before publishing
2. **Use editorial workflow**: Enable for approval process
3. **Organize content**: Use categories and tags
4. **Regular backups**: Content is in Git, but export important posts
5. **Monitor activity**: Check Git commits for changes

---

## Adding New Collections

To add a new content type:

1. Edit `admin/config.yml`
2. Add a new collection:
   ```yaml
   - name: "gallery"
     label: "Gallery"
     folder: "content/gallery"
     create: true
     fields:
       - {label: "Title", name: "title", widget: "string"}
       - {label: "Image", name: "image", widget: "image"}
   ```
3. Create the folder: `content/gallery/`
4. Restart CMS or refresh page

---

## Support

For issues or questions:

1. Check this documentation
2. Review Netlify CMS docs: https://www.netlifycms.org/docs/
3. Check Vercel deployment logs
4. Contact the website administrator

---

**Last Updated:** 2024  
**Version:** 1.0
