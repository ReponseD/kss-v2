# GitHub OAuth Setup for Netlify CMS (Vercel Hosting)

Since you're hosting on Vercel (not Netlify), you need to use GitHub OAuth instead of Netlify Identity.

## Step 1: Create GitHub OAuth App

1. Go to GitHub → Settings → Developer settings → OAuth Apps
2. Click "New OAuth App"
3. Fill in:
   - **Application name**: "KSS CMS" (or any name)
   - **Homepage URL**: `https://www.kagaramasec.org`
   - **Authorization callback URL**: `https://api.netlify.com/auth/done`
4. Click "Register application"
5. **Copy the Client ID** (you'll need this)
6. Click "Generate a new client secret"
7. **Copy the Client Secret** (you'll need this too)

## Step 2: Update config.yml

Update `admin/config.yml` with your GitHub repository information:

```yaml
backend:
  name: github
  repo: your-username/your-repo-name  # ← Replace with your GitHub repo
  branch: main  # ← Your default branch
```

**Example:**
```yaml
backend:
  name: github
  repo: kagaramasec/kss-v2
  branch: main
```

## Step 3: Set Up Netlify (for OAuth only)

Even though you're hosting on Vercel, you still need Netlify for the OAuth proxy:

1. Go to https://app.netlify.com
2. Add your GitHub repository (this is just for OAuth, not hosting)
3. Go to Site Settings → Identity → Services → GitHub
4. Enter:
   - **Client ID**: (from Step 1)
   - **Client Secret**: (from Step 1)
5. Save

## Step 4: Update Your Repository

1. Make sure your repository is **public** (or configure OAuth for private repos)
2. Users need **write access** to the repository
3. Commit and push the updated `config.yml`

## Step 5: Test

1. Visit https://www.kagaramasec.org/admin
2. Click "Login with GitHub"
3. Authorize the application
4. You should now have access to the CMS!

---

## Important Notes

- **Repository must be public** (or users need explicit access)
- **Users authenticate with GitHub** (not email/password)
- **Users need write access** to commit changes
- **Netlify is only used for OAuth** (not hosting)

---

## Adding Users

Users need:
1. A GitHub account
2. Access to your repository (if private)
3. Write permissions (to commit changes)

To add users to a private repository:
1. Go to repository Settings → Collaborators
2. Add users and give them "Write" access

