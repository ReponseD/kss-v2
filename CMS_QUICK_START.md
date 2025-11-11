# Netlify CMS Quick Start Guide

## 🚀 5-Minute Setup

### Step 1: Enable Netlify Identity (2 minutes)

1. Go to https://app.netlify.com
2. Add your GitHub repository
3. Go to **Site Settings → Identity**
4. Click **"Enable Identity"**
5. Go to **Services → Git Gateway**
6. Click **"Enable Git Gateway"**

### Step 2: Configure Registration (1 minute)

1. In Identity settings, go to **Registration**
2. Choose **"Invite only"** (recommended)
3. Save settings

### Step 3: Create Admin Account (1 minute)

1. Go to **Identity → Invite users**
2. Enter your email
3. Check your email and accept invitation
4. Set your password

### Step 4: Access CMS (1 minute)

1. Visit: **https://www.kagaramasec.org/admin**
2. Log in with your credentials
3. Start creating content!

---

## ✅ What's Included

- ✅ **Admin Panel**: `/admin/index.html`
- ✅ **CMS Config**: `/admin/config.yml`
- ✅ **Content Folders**: 
  - `content/posts/` - Articles
  - `content/media/` - Videos
  - `content/events/` - Events/Announcements
- ✅ **Uploads Folder**: `assets/uploads/`
- ✅ **Example Content**: Sample files in each collection

---

## 📚 Documentation

- **Full Setup Guide**: `NETLIFY_CMS_SETUP.md`
- **Student Guide**: `STUDENT_GUIDE.md`
- **This Quick Start**: `CMS_QUICK_START.md`

---

## 🎯 Next Steps

1. **Add Users**: Invite students/teachers via Netlify Identity
2. **Test CMS**: Create a test post to verify everything works
3. **Train Users**: Share `STUDENT_GUIDE.md` with media club
4. **Start Creating**: Begin adding real content!

---

## ⚠️ Important Notes

- **Vercel Deployment**: Your site is on Vercel, but you need Netlify for CMS authentication
- **Git Commits**: Every change creates a Git commit
- **Auto-Deploy**: Vercel will rebuild when you commit
- **Branch**: Make sure `config.yml` uses the correct branch (usually `main`)

---

## 🆘 Need Help?

Check `NETLIFY_CMS_SETUP.md` for detailed troubleshooting.

**Ready to go!** 🎉

