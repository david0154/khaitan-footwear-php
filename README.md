# 👟 Khaitan Footwear - Complete Website System

## ✨ Overview
Professional footwear manufacturer website with powerful admin control panel. Built with PHP, MySQL, and Tailwind CSS.

## 🎯 Key Features

### 🎨 **Branding Control (NEW!)**
- ✅ **Logo Upload** - Upload JPG/PNG logo from admin panel
- ✅ **Favicon Upload** - Custom browser favicon (ICO/PNG)
- ✅ **Dynamic Display** - Logo and favicon automatically show on frontend
- ✅ **Fallback Text** - If no logo uploaded, shows site name

### 🌅 **Hero Banner Management (NEW!)**
- ✅ **Image Upload** - Custom hero background image
- ✅ **Editable Title** - Change hero heading from admin
- ✅ **Editable Subtitle** - Update hero description
- ✅ **Button Control** - Customize button text and link
- ✅ **Full Control** - No coding required

### 🔗 **Social Media Integration (NEW!)**
- ✅ **Show/Hide Toggle** - Master switch to enable/disable social media
- ✅ **Supported Platforms:**
  - Facebook
  - Instagram
  - Twitter/X
  - LinkedIn
  - YouTube
  - WhatsApp (with number)
- ✅ **Top Bar Display** - Shows in header when enabled
- ✅ **Footer Display** - Also appears in footer
- ✅ **Admin Control** - Add/remove/hide links anytime

### 📦 **Product Management (UPDATED!)**
- ✅ **Article Number as Identity** - No product name field needed
- ✅ **Article Code is Product Name** - Simplified workflow
- ✅ **Full CRUD** - Create, Read, Update, Delete
- ✅ **Image Upload** - Product photos
- ✅ **Categories** - Gents, Ladies, Kids, Sports
- ✅ **Featured Products** - Homepage showcase
- ✅ **Status Control** - Active/Inactive
- ✅ **No Prices** - B2B focused (as requested)

### 📧 **Email System**
- ✅ SMTP Configuration from admin
- ✅ Contact form notifications
- ✅ Customer confirmation emails
- ✅ HTML email templates

### 📈 **Analytics Dashboard**
- ✅ Product statistics
- ✅ Inquiry tracking
- ✅ Category performance
- ✅ User management

### 🎨 **Design**
- ✅ Ashoka Red color scheme (#dc2626)
- ✅ Clean white header
- ✅ Professional navigation
- ✅ Mobile responsive
- ✅ Modern animations

## 🛠️ Admin Panel Features

### 🏛️ Dashboard
- Overview statistics
- Quick access to all features
- Recent activity

### 💞 Branding Settings (`/admin/branding-settings.php`)
```
✅ Upload Logo (JPG/PNG, 200x80px recommended)
✅ Upload Favicon (ICO/PNG, 32x32px recommended)
✅ Facebook URL
✅ Instagram URL
✅ Twitter/X URL
✅ LinkedIn URL
✅ YouTube URL
✅ WhatsApp Number (with country code)
✅ Show/Hide Social Media Toggle
```

### 🌅 Hero Banner Settings (`/admin/banner-settings.php`)
```
✅ Upload Background Image (1920x800px recommended)
✅ Edit Hero Title
✅ Edit Hero Subtitle
✅ Edit Button Text
✅ Edit Button Link
✅ Preview Homepage button
```

### 📦 Product Management
```
✅ Add/Edit Products (Article Number as identity)
✅ Upload Product Images
✅ Assign Categories
✅ Mark as Featured
✅ Active/Inactive Status
✅ Sizes & Colors
```

### 📂 Category Management
```
✅ Add/Edit Categories
✅ Upload Category Images
✅ Set Display Order
✅ Active/Inactive Status
```

### 📧 Contact Management
```
✅ View Inquiries
✅ Mark as Read/Replied
✅ Export Leads
```

### 👥 User Management
```
✅ Add/Edit Users
✅ Role Assignment (Admin/Manager/Staff)
✅ Active/Inactive Status
```

## 🚀 Installation

### Quick Install (3 Steps)

```bash
# 1. Clone Repository
cd /home/yourusername
git clone https://github.com/david0154/khaitan-footwear-php.git khaitan
cd khaitan

# 2. Set Permissions
chmod -R 775 uploads/
chmod 664 config.php

# 3. Visit Installer
http://yourdomain.com/install.php
```

### Installer Steps:
1. **Database Setup** - Enter MySQL credentials
2. **Company Info** - Enter business name
3. **Admin Account** - Create admin login

### Default Login:
```
Email: admin@khaitanfootwear.in
Password: admin123
```

⚠️ **Change password immediately after first login!**

## 📚 How to Use Admin Features

### Upload Logo & Favicon
1. Login to admin panel
2. Go to **Branding Settings** in sidebar
3. Upload logo (JPG/PNG)
4. Upload favicon (ICO/PNG)
5. Click "Save All Settings"
6. Check homepage - logo appears automatically!

### Manage Social Media
1. Go to **Branding Settings**
2. Scroll to "Social Media Links" section
3. Enter URLs for each platform
4. Toggle "Show on Website" to enable/disable
5. Save settings
6. Social icons appear in header and footer

### Change Hero Banner
1. Go to **Hero Banner Settings** in sidebar
2. Upload new background image
3. Edit title, subtitle, button text
4. Click "Preview Homepage" to see changes
5. Save when satisfied

### Add Products (Article Number Only)
1. Go to **Products** > **Add New**
2. Enter **Article Number** (e.g., KH-2024-001)
3. Select Category
4. Add description, sizes, colors
5. Upload product image
6. Mark as featured (optional)
7. Save product

**Note:** Product name = Article number (no separate name field)

## 📁 File Structure

```
/
├── install.php                    🚀 3-step installer
├── cleanup-database.php           🧹 Database cleanup tool
├── repair-database.php            🔧 Database repair tool
├── config.php                     ⚙️ Auto-generated
├── database.sql                   📊 Schema with defaults
│
├── index.php                      🏠 Homepage
├── products.php                   🛒 Product listing
├── product.php                    📦 Product details
├── contact.php                    📧 Contact form
├── about.php                      ℹ️ About page
│
├── admin/
│   ├── login.php                  🔐 Admin login
│   ├── dashboard.php              📊 Dashboard
│   ├── branding-settings.php      🎨 NEW - Logo/Favicon/Social
│   ├── banner-settings.php        🌅 NEW - Hero banner
│   ├── products.php               📦 Product list
│   ├── product-edit.php           ✏️ Add/Edit (Article No)
│   ├── categories.php             📂 Category management
│   ├── contacts.php               📧 Inquiry management
│   ├── users.php                  👥 User management
│   ├── email-settings.php         ⚙️ SMTP config
│   └── settings.php               🛠️ General settings
│
├── includes/
│   ├── header.php                 📝 Header (logo/social)
│   ├── footer.php                 📝 Footer (social)
│   └── email.php                  ✉️ Email service
│
└── uploads/
    ├── logo.*                     🖼️ Site logo
    ├── favicon.*                  🔖 Site favicon
    ├── products/                  📦 Product images
    ├── categories/                📂 Category images
    └── banners/                   🌅 Hero banner images
```

## 💾 Database Tables

### 1. `users`
```sql
id, name, email, password, role, status, created_at, updated_at
```

### 2. `categories`
```sql
id, name, slug, description, image, status, order_num, created_at
```

### 3. `products`
```sql
id, category_id, name (=article_code), slug, article_code, 
description, sizes, colors, primary_image, images,
is_featured, status, created_at, updated_at
```

### 4. `banners`
```sql
id, title, subtitle, image, button_text, button_link, 
status, order_num, created_at
```

### 5. `contacts`
```sql
id, name, email, phone, company, message, 
status, created_at
```

### 6. `settings`
```sql
id, key, value
```

**Settings Keys:**
- `site_logo` - Logo filename
- `site_favicon` - Favicon filename
- `site_name` - Company name
- `site_tagline` - Tagline text
- `site_phone` - Phone number
- `site_email` - Email address
- `hero_banner_image` - Hero background
- `hero_title` - Hero heading
- `hero_subtitle` - Hero description
- `hero_button_text` - Button text
- `hero_button_link` - Button URL
- `facebook_url` - Facebook link
- `instagram_url` - Instagram link
- `twitter_url` - Twitter link
- `linkedin_url` - LinkedIn link
- `youtube_url` - YouTube link
- `whatsapp_number` - WhatsApp number
- `show_social_media` - 1/0 toggle
- `home_about` - About section text

## 🎨 Color Scheme

**Primary Colors:**
```css
--red-primary: #dc2626
--red-dark: #b91c1c
--red-light: #ef4444
--orange-accent: #f97316
```

**Usage:**
- Headers: Red gradients
- Buttons: Red to orange gradient
- Links: Red on hover
- Admin: Red accents

## ⚙️ Configuration

### Email Settings
**Gmail Example:**
```
Host: smtp.gmail.com
Port: 587
Username: your-email@gmail.com
Password: [App Password]
```

**Custom SMTP:**
```
Host: mail.yourdomain.com
Port: 587/465
Username: noreply@yourdomain.com
Password: [Your Password]
```

## 🔒 Security Features

- ✅ Password hashing (bcrypt)
- ✅ SQL injection protection (PDO prepared statements)
- ✅ Session management
- ✅ File upload validation
- ✅ Role-based access control
- ✅ XSS protection (htmlspecialchars)

## 🐛 Troubleshooting

### Database Errors?
```bash
# Run repair script
http://yourdomain.com/repair-database.php
```

### Fresh Start?
```bash
# Run cleanup script
http://yourdomain.com/cleanup-database.php
# Then run installer again
```

### Foreign Key Errors?
```
Database schema now includes:
SET FOREIGN_KEY_CHECKS = 0;
...drops tables...
SET FOREIGN_KEY_CHECKS = 1;
```

### Logo Not Showing?
1. Check file uploaded to `/uploads/`
2. Check `settings` table has `site_logo` key
3. Check file permissions (775)
4. Clear browser cache

### Social Media Not Showing?
1. Check **Show on Website** toggle is ON
2. Check URLs are entered correctly
3. Check `show_social_media` = '1' in settings table

## 📝 Admin Panel URLs

- Dashboard: `/admin/dashboard.php`
- Branding: `/admin/branding-settings.php` 🆕
- Hero Banner: `/admin/banner-settings.php` 🆕
- Products: `/admin/products.php`
- Add Product: `/admin/product-edit.php`
- Categories: `/admin/categories.php`
- Contacts: `/admin/contacts.php`
- Users: `/admin/users.php`
- Email Settings: `/admin/email-settings.php`

## ✅ Testing Checklist

### Frontend:
- [ ] Logo displays in header
- [ ] Favicon shows in browser tab
- [ ] Social media icons appear (when enabled)
- [ ] Hero banner shows custom image
- [ ] Products display with article numbers
- [ ] Contact form submits
- [ ] Email notifications work
- [ ] Mobile responsive

### Admin:
- [ ] Login works
- [ ] Logo upload saves and displays
- [ ] Favicon upload saves and displays
- [ ] Social media toggle works
- [ ] Hero banner upload works
- [ ] Product add/edit (article number only)
- [ ] Categories management
- [ ] Contact inquiries visible
- [ ] Email settings configurable

## 📚 Change Log

### v2.0 (Latest) - February 2026
- ✅ Added logo upload from admin
- ✅ Added favicon upload from admin
- ✅ Added social media management with show/hide
- ✅ Added hero banner image upload
- ✅ **Removed product name field** - Article number is now the identity
- ✅ Updated header/footer to show logo and social dynamically
- ✅ Fixed database foreign key constraints
- ✅ Added cleanup and repair scripts

### v1.0 - Initial Release
- Basic product management
- Category system
- Contact form
- Admin panel
- Email notifications

## 📞 Support

**Repository:** https://github.com/david0154/khaitan-footwear-php

**Issues:** Report bugs or request features via GitHub Issues

## 📄 License

MIT License - Free to use and modify

## 🚀 Quick Start Commands

```bash
# Clone
git clone https://github.com/david0154/khaitan-footwear-php.git

# Setup
cd khaitan-footwear-php
chmod -R 775 uploads/

# Install
# Visit: http://yourdomain.com/install.php

# Login
# Visit: http://yourdomain.com/admin/login.php
# Email: admin@khaitanfootwear.in
# Password: admin123
```

---

**Made with ❤️ for Khaitan Footwear** | **Last Updated:** February 18, 2026
