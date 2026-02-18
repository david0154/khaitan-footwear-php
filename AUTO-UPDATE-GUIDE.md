# 🔄 Auto-Update System Guide

## ✨ Features

Your website can now automatically update from GitHub!

### 🎯 What You Get:
1. ✅ **One-Click Updates** - Update from admin panel with one click
2. ✅ **Auto-Update Mode** - Website updates automatically when new version available
3. ✅ **Automatic Backups** - Every update creates a backup automatically
4. ✅ **Version Tracking** - See current and latest versions
5. ✅ **Changelog Viewer** - View recent updates from GitHub
6. ✅ **Safe Updates** - Rollback possible if something goes wrong

---

## 🚀 How to Use

### Method 1: One-Click Update (Recommended)

1. **Login to Admin Panel**
   ```
   http://yourdomain.com/admin/login.php
   ```

2. **Go to Updates**
   - Click "🔄 Updates" in sidebar
   - Or visit: `/admin/updates.php`

3. **Check for Updates**
   - Page loads automatically
   - Shows: Current version, Latest version, Status

4. **Update if Available**
   - If update available, orange banner shows
   - Click "🔄 Update Now" button
   - Confirm the update
   - Wait 10-30 seconds
   - Done! ✅

**Features:**
- ✅ Automatic backup before update
- ✅ Shows what's new (changelog)
- ✅ Safe and tested
- ✅ No SSH needed

---

### Method 2: Auto-Update Mode

**Enable automatic updates to get latest features without clicking:**

1. **Go to Updates Page**
   ```
   /admin/updates.php
   ```

2. **Enable Auto-Update**
   - Find "⚙️ Auto-Update Settings" section
   - Check the box: "Enable automatic updates"
   - Click "Save Settings"

3. **Setup Cron Job** (One-time setup)
   ```bash
   # SSH to your server
   ssh username@yourdomain.com
   
   # Edit crontab
   crontab -e
   
   # Add this line (checks every hour):
   0 * * * * php /home/zfugpsef/khaitan/auto-update.php
   ```

4. **How it Works:**
   - Cron runs every hour
   - Checks GitHub for new version
   - If found: Creates backup → Updates → Done!
   - If not: Does nothing

**⚠️ Important:**
- Auto-update is **NOT recommended** for live production sites
- Best for development/testing environments
- Always test updates on staging first

---

## 📁 Files Created

### 1. `/admin/updates.php`
**Admin panel page to manage updates**

**Features:**
- Check current version
- Check latest GitHub version
- One-click update button
- View changelog (last 10 commits)
- Enable/disable auto-update
- Shows update status

### 2. `/auto-update.php`
**Cron script for automatic updates**

**What it does:**
- Runs via cron job
- Checks if auto-update enabled
- Fetches latest GitHub commit
- Compares with current version
- Creates backup
- Pulls latest code
- Updates version file

### 3. `/VERSION`
**Current version identifier**

Contains: 7-character commit SHA (e.g., `1.0.0` or `a1b2c3d`)

### 4. `/backups/` (Auto-created)
**Backup directory**

Every update creates:
```
backups/
├── backup_2026-02-18_19-30-00/
├── backup_2026-02-19_10-15-00/
└── backup_2026-02-20_14-45-00/
```

---

## 🔧 Setup Instructions

### Step 1: Initialize Git Repository

```bash
# SSH to server
cd /home/zfugpsef/khaitan

# Initialize git (if not already)
git init

# Add GitHub remote
git remote add origin https://github.com/david0154/khaitan-footwear-php.git

# Pull latest code
git pull origin main

# Set permissions
chmod 755 auto-update.php
chmod 755 admin/updates.php
chmod 666 VERSION
mkdir -p backups
chmod 755 backups
```

### Step 2: Test Manual Update

```bash
# Visit admin panel
http://yourdomain.com/admin/updates.php

# Click "Update Now" if available
# Check if it works!
```

### Step 3: Enable Auto-Update (Optional)

```bash
# Add cron job
crontab -e

# Add line (every hour at minute 0):
0 * * * * php /home/zfugpsef/khaitan/auto-update.php >> /home/zfugpsef/khaitan/update.log 2>&1

# Or every 6 hours:
0 */6 * * * php /home/zfugpsef/khaitan/auto-update.php >> /home/zfugpsef/khaitan/update.log 2>&1

# Save and exit
```

---

## 🛡️ Safety Features

### Automatic Backups

Every update creates a full backup:
```
backups/backup_YYYY-MM-DD_HH-MM-SS/
```

**Restore from backup:**
```bash
cd /home/zfugpsef/khaitan
cp -r backups/backup_2026-02-18_19-30-00/* .
```

### Update Checks

1. ✅ Checks if update available
2. ✅ Creates backup before updating
3. ✅ Only updates if newer version available
4. ✅ Logs all update attempts
5. ✅ Won't update more than once per hour (auto-mode)

### Rollback Process

**If update fails or causes issues:**

```bash
# Method 1: Via backup
cd /home/zfugpsef/khaitan
rm -rf *
cp -r backups/backup_LATEST/* .

# Method 2: Via git
git reset --hard HEAD~1

# Method 3: Specific commit
git checkout abc123def
```

---

## 📊 Admin Panel Features

### Dashboard View

```
┌─────────────────────────────────────┐
│  Current Version    Latest Version  │
│     1.0.0              1.0.1        │
│                                     │
│  Status: Update Available ⚠️        │
└─────────────────────────────────────┘
```

### Update Banner

When update available:
```
🎉 New Update Available!
A new version is available. Update now to get 
the latest features and bug fixes.

[🔄 Update Now]
```

### Changelog Display

Shows last 10 commits:
```
📝 Recent Updates

├─ Add auto-update system
│  abc123d by David
│  Feb 18, 2026 19:30
│
├─ Fix header logo display
│  def456a by David  
│  Feb 18, 2026 19:20
│
└─ Add contact info management
   ghi789b by David
   Feb 18, 2026 19:10
```

---

## ⚙️ Configuration

### GitHub Settings

Edit in `/admin/updates.php`:

```php
$githubOwner = 'david0154';
$githubRepo = 'khaitan-footwear-php';
$githubBranch = 'main';
```

### Update Frequency

Edit in crontab:

```bash
# Every hour
0 * * * * php /path/to/auto-update.php

# Every 6 hours
0 */6 * * * php /path/to/auto-update.php

# Daily at 3 AM
0 3 * * * php /path/to/auto-update.php

# Every 30 minutes
*/30 * * * * php /path/to/auto-update.php
```

---

## 🐛 Troubleshooting

### Issue: "Update Failed"

**Solution 1: Check Git Status**
```bash
cd /home/zfugpsef/khaitan
git status
# If files modified:
git reset --hard HEAD
git pull origin main
```

**Solution 2: Check Permissions**
```bash
chmod -R 755 /home/zfugpsef/khaitan
chmod -R 775 backups/
```

**Solution 3: Manual Update**
```bash
cd /home/zfugpsef/khaitan
git pull origin main
```

### Issue: "Backup Failed"

**Solution:**
```bash
# Create backups directory
mkdir -p /home/zfugpsef/khaitan/backups
chmod 775 /home/zfugpsef/khaitan/backups
```

### Issue: "Cron Not Working"

**Check cron logs:**
```bash
tail -f /home/zfugpsef/khaitan/update.log
```

**Verify cron:**
```bash
crontab -l
```

**Test manually:**
```bash
php /home/zfugpsef/khaitan/auto-update.php
```

### Issue: "Already Up to Date" But Update Shows

**Solution:**
```bash
# Reset VERSION file
cd /home/zfugpsef/khaitan
git rev-parse --short HEAD > VERSION
```

---

## 📈 Update Flow

### Manual Update Flow

```
1. Admin clicks "Update Now"
   ↓
2. System creates backup
   ↓
3. Git pulls latest from GitHub
   ↓
4. Updates VERSION file
   ↓
5. Shows success message
   ↓
6. Website updated! ✅
```

### Auto-Update Flow

```
1. Cron runs every hour
   ↓
2. Checks if auto-update enabled
   ↓
3. Checks GitHub for new commits
   ↓
4. If new version found:
   ├─ Create backup
   ├─ Pull changes
   └─ Update VERSION
   ↓
5. Website updated! ✅
```

---

## 📋 Best Practices

### ✅ DO:

1. **Test on Staging First**
   - Always test updates on dev/staging site
   - Check all features work
   - Then update production

2. **Keep Backups**
   - Keep last 5-10 backups
   - Store important backups offline
   - Test restore process

3. **Monitor Updates**
   - Check update logs regularly
   - Verify website after updates
   - Watch for errors

4. **Schedule Wisely**
   - Update during low-traffic hours
   - Not during business hours
   - Have rollback plan ready

### ❌ DON'T:

1. **Don't** enable auto-update on production without testing
2. **Don't** delete backups immediately
3. **Don't** modify files directly if auto-update enabled
4. **Don't** skip checking changelog

---

## 🔐 Security

### Access Control

- Updates page requires admin login
- Only admins can trigger updates
- Cron script checks settings table

### Safe Operations

- All updates use `git pull` (safe)
- Automatic backups before changes
- No file deletions without backup
- Version tracking in database

---

## 📞 Support

### Check Update Status

```bash
# Current version
cat VERSION

# Latest commit
git log -1 --oneline

# Check for updates
git fetch
git status
```

### Manual Commands

```bash
# Update manually
cd /home/zfugpsef/khaitan
git pull origin main

# Check what's new
git log --oneline -10

# View specific commit
git show abc123
```

---

## ✅ Update Checklist

**Before Update:**
- [ ] Backup database
- [ ] Check current version
- [ ] Read changelog
- [ ] Low traffic time?

**During Update:**
- [ ] Click "Update Now"
- [ ] Wait for completion
- [ ] Check success message

**After Update:**
- [ ] Test homepage
- [ ] Test admin panel
- [ ] Check products page
- [ ] Verify contact form
- [ ] Check all features

---

## 🎉 Summary

You now have:

✅ One-click updates from admin panel  
✅ Auto-update option for convenience  
✅ Automatic backups before every update  
✅ Changelog viewer to see what's new  
✅ Safe rollback if needed  
✅ Version tracking  
✅ GitHub integration  

**Your website stays up to date automatically!** 🚀

---

**Repository:** https://github.com/david0154/khaitan-footwear-php  
**Admin Panel:** http://yourdomain.com/admin/updates.php  
**Last Updated:** February 18, 2026
