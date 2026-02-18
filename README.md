# 🚀 Khaitan Footwear - Professional PHP Application

## ✨ Features

### 🎨 **Modern 3D UI Design**
- Beautiful gradient cards with hover effects
- Smooth animations and transitions
- Responsive design (mobile, tablet, desktop)
- Orange/Red brand theme

### 👔 **Frontend Features**
- Homepage with hero banners
- Product catalog with advanced filters
- Category pages (Gents, Ladies, Kids, Sports)
- Product detail pages with image gallery
- Contact form with email notifications
- About page
- SEO-friendly URLs

### 🎛️ **Admin Panel Features**
- 📊 **Analytics Dashboard** with charts and trends
- 📦 **Product Management** (add/edit/delete, image upload)
- 🏷️ **Category Management** (Gents, Ladies, Kids collections)
- 🎯 **Banner Management** for homepage
- 📧 **Contact Inquiries** with status tracking
- 👥 **User Management** (multi-role support)
- ⚙️ **Settings** (site configuration)
- ✉️ **Email Settings** (SMTP configuration)

### 📧 **Email System**
- Automatic email notifications on contact form submission
- Confirmation emails to customers
- Admin notification emails
- SMTP support (Gmail, custom servers)
- Beautiful HTML email templates

### 📊 **Analytics Features**
- Product statistics by category
- Inquiry trends (last 7 days)
- Real-time metrics
- Featured products tracking

## 🎯 Categories

1. **Gents Collection** - Premium footwear for men
2. **Ladies Collection** - Stylish footwear for women
3. **Kids Collection** - Comfortable shoes for kids
4. **Sports Shoes** - Athletic and sports footwear

## 🛠️ Installation

### Quick Install (3 Steps)

1. **Upload files to your server**
```bash
git clone https://github.com/david0154/khaitan-footwear-php.git
cd khaitan-footwear-php
chmod -R 775 uploads/
```

2. **Visit installer**
```
http://yourdomain.com/install.php
```

3. **Follow 3-step wizard:**
   - Step 1: Enter database credentials
   - Step 2: Enter company name
   - Step 3: Create admin account

**Done!** 🎉

### Default Login
- Email: `admin@khaitanfootwear.in`
- Password: `admin123`

**⚠️ Change password after first login!**

## ⚙️ Email Setup

1. Login to admin panel
2. Go to **Email Settings**
3. Enter SMTP details:
   - **Gmail:** smtp.gmail.com, port 587
   - **Username:** your-email@gmail.com
   - **Password:** Use App Password (not regular password)

### Gmail App Password
1. Go to Google Account Settings
2. Security → 2-Step Verification
3. App Passwords → Generate new
4. Use that password in Email Settings

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite OR Nginx
- GD Library (for image uploads)

## 📁 File Structure

```
/
├── install.php              # One-click installer
├── database.sql            # Database schema
├── config.php              # Auto-generated config
├── index.php               # Homepage
├── products.php            # Product listing
├── product.php             # Product details
├── contact.php             # Contact form (with email)
├── about.php               # About page
├── admin/                  # Admin panel
│   ├── login.php
│   ├── dashboard.php
│   ├── analytics.php       # 📊 NEW: Sales analytics
│   ├── products.php
│   ├── product-edit.php
│   ├── categories.php
│   ├── banners.php
│   ├── contacts.php
│   ├── users.php
│   ├── email-settings.php  # ✉️ NEW: Email config
│   └── settings.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── email.php           # ✉️ NEW: Email service
└── uploads/
    ├── products/
    ├── categories/
    └── banners/
```

## 🎨 Design Highlights

### 3D Cards with Gradients
- Orange to Red gradients for primary actions
- Blue to Purple for analytics
- Green to Teal for success states
- Smooth hover animations

### Modern UI Elements
- Rounded corners (rounded-xl, rounded-2xl)
- Box shadows (shadow-lg, shadow-xl)
- Transform effects (hover:scale-105)
- Backdrop blur effects

## 🔒 Security Features

- Password hashing (bcrypt)
- SQL injection protection (PDO prepared statements)
- XSS protection (htmlspecialchars)
- Session management
- File upload validation
- .htaccess protection for sensitive files

## 🚀 Performance

- Pure PHP (no framework overhead)
- Optimized database queries
- CDN for Tailwind CSS
- Minimal JavaScript
- Fast page loads

## 📧 Contact

For support or customization:
- Email: admin@khaitanfootwear.in
- Website: https://github.com/david0154/khaitan-footwear-php

## 📝 License

MIT License - Free to use and modify

## 🎉 What's New in v2.0

✅ **3D Modern UI Design**
✅ **Sales Analytics Dashboard**
✅ **Email Notification System**
✅ **SMTP Configuration Panel**
✅ **Gents/Ladies/Kids Categories**
✅ **Contact Form Emails**
✅ **Advanced Product Management**
✅ **Improved Admin UX**

---

**Built with ❤️ for Khaitan Footwear**