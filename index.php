<?php
require_once 'includes/header.php';

// Get banners
$banners = $pdo->query("SELECT * FROM banners WHERE status = 'active' ORDER BY order_num LIMIT 1")->fetchAll();

// Get categories with a sample product image from each
$categoriesQuery = "
    SELECT c.*, 
           (SELECT p.primary_image 
            FROM products p 
            WHERE p.category_id = c.id 
            AND p.status = 'active' 
            AND p.primary_image IS NOT NULL 
            LIMIT 1) as sample_image
    FROM categories c 
    WHERE c.status = 'active' 
    ORDER BY c.order_num
";
$categories = $pdo->query($categoriesQuery)->fetchAll();

// Get featured products
$products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.is_featured = 1 AND p.status = 'active' LIMIT 8")->fetchAll();

$tagline = $settings['site_tagline'] ?? 'Leading Manufacturer of Quality Footwear';
$about_text = $settings['home_about'] ?? 'We are one of the leading footwear manufacturers in India, offering stylish, comfortable and durable products.';

// Hero banner settings
$hero_image = $settings['hero_banner_image'] ?? '';
$hero_title = $settings['hero_title'] ?? 'Welcome to Khaitan Footwear';
$hero_subtitle = $settings['hero_subtitle'] ?? 'Leading manufacturer and supplier of quality footwear';
$hero_button_text = $settings['hero_button_text'] ?? 'View Products';
$hero_button_link = $settings['hero_button_link'] ?? 'products.php';
?>

<!-- Hero Section with Background Image -->
<section class="relative bg-gray-900 text-white py-32 overflow-hidden" style="min-height: 600px;">
    <?php if ($hero_image): ?>
    <!-- Custom uploaded background -->
    <div class="absolute inset-0 z-0">
        <img src="uploads/<?= htmlspecialchars($hero_image) ?>" alt="Hero Banner" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-transparent"></div>
    </div>
    <?php else: ?>
    <!-- Default gradient background -->
    <div class="absolute inset-0 bg-gradient-to-r from-red-600 via-red-700 to-red-900 z-0">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-6xl md:text-7xl font-black mb-6 leading-tight drop-shadow-2xl">
                <?= htmlspecialchars($hero_title) ?>
            </h1>
            <p class="text-2xl md:text-3xl font-light mb-8 text-red-100 drop-shadow-lg">
                <?= htmlspecialchars($hero_subtitle) ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= htmlspecialchars($hero_button_link) ?>" class="bg-white text-red-600 px-10 py-4 rounded-full text-xl font-bold hover:bg-red-50 transform hover:scale-105 transition shadow-2xl">
                    <?= htmlspecialchars($hero_button_text) ?>
                </a>
                <a href="contact.php" class="border-2 border-white text-white px-10 py-4 rounded-full text-xl font-bold hover:bg-white hover:text-red-600 transform hover:scale-105 transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8">About Us</h2>
            <p class="text-xl text-gray-600 leading-relaxed">
                <?= nl2br(htmlspecialchars($about_text)) ?>
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">25+</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Years Experience</h3>
                    <p class="text-gray-600">Industry leader since 1995</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">500+</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Product Range</h3>
                    <p class="text-gray-600">Wide variety of styles</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">100%</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Quality Assured</h3>
                    <p class="text-gray-600">Durable & comfortable</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Carousel Section -->
<?php if (!empty($categories)): ?>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-4xl md:text-5xl font-bold text-center text-gray-800 mb-16">Our Collections</h2>
        
        <!-- Carousel Container -->
        <div class="relative">
            <!-- Previous Button -->
            <button onclick="prevCategory()" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-red-600 hover:text-white text-gray-800 rounded-full p-4 shadow-xl transition transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Next Button -->
            <button onclick="nextCategory()" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white hover:bg-red-600 hover:text-white text-gray-800 rounded-full p-4 shadow-xl transition transform hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Carousel Items -->
            <div id="categoryCarousel" class="overflow-hidden">
                <div id="categoryTrack" class="flex transition-transform duration-500 ease-in-out">
                    <?php foreach ($categories as $cat): ?>
                    <div class="category-slide flex-shrink-0 px-4" style="width: 25%;">
                        <a href="products.php?category=<?= $cat['slug'] ?>" class="group block">
                            <div class="relative overflow-hidden rounded-2xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2">
                                <?php if ($cat['sample_image']): ?>
                                <!-- Show actual product image from category -->
                                <div class="aspect-square bg-white relative">
                                    <img src="uploads/products/<?= htmlspecialchars($cat['sample_image']) ?>" 
                                         alt="<?= htmlspecialchars($cat['name']) ?>" 
                                         class="w-full h-full object-contain p-6">
                                    <!-- Overlay with category info -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-red-600 via-red-600/50 to-transparent opacity-90 flex items-end p-6">
                                        <div class="text-white w-full">
                                            <h3 class="text-2xl font-bold mb-2"><?= htmlspecialchars($cat['name']) ?></h3>
                                            <p class="text-red-100 text-sm"><?= htmlspecialchars($cat['description']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- Fallback to gradient if no image -->
                                <div class="aspect-square bg-gradient-to-br from-red-500 to-red-700 flex items-center justify-center p-8">
                                    <div class="text-center text-white">
                                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-2xl font-bold mb-2"><?= htmlspecialchars($cat['name']) ?></h3>
                                        <p class="text-red-100 text-sm"><?= htmlspecialchars($cat['description']) ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Dots Indicator -->
            <div class="flex justify-center mt-8 space-x-2">
                <?php for ($i = 0; $i < count($categories); $i++): ?>
                <button onclick="goToCategory(<?= $i ?>)" class="category-dot w-3 h-3 rounded-full bg-gray-300 hover:bg-red-600 transition" data-index="<?= $i ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<script>
let currentCategory = 0;
const totalCategories = <?= count($categories) ?>;
const visibleSlides = 4;
const maxIndex = Math.max(0, totalCategories - visibleSlides);

function updateCarousel() {
    const track = document.getElementById('categoryTrack');
    const offset = -(currentCategory * (100 / visibleSlides));
    track.style.transform = `translateX(${offset}%)`;
    
    // Update dots
    document.querySelectorAll('.category-dot').forEach((dot, index) => {
        if (index === currentCategory) {
            dot.classList.remove('bg-gray-300');
            dot.classList.add('bg-red-600');
        } else {
            dot.classList.remove('bg-red-600');
            dot.classList.add('bg-gray-300');
        }
    });
}

function nextCategory() {
    if (currentCategory < maxIndex) {
        currentCategory++;
    } else {
        currentCategory = 0; // Loop back to start
    }
    updateCarousel();
}

function prevCategory() {
    if (currentCategory > 0) {
        currentCategory--;
    } else {
        currentCategory = maxIndex; // Loop to end
    }
    updateCarousel();
}

function goToCategory(index) {
    currentCategory = Math.min(index, maxIndex);
    updateCarousel();
}

// Auto-rotate every 3 seconds
let autoRotate = setInterval(() => {
    nextCategory();
}, 3000);

// Pause auto-rotate on hover
const carousel = document.getElementById('categoryCarousel');
carousel.addEventListener('mouseenter', () => {
    clearInterval(autoRotate);
});

carousel.addEventListener('mouseleave', () => {
    autoRotate = setInterval(() => {
        nextCategory();
    }, 3000);
});

// Initialize
updateCarousel();

// Responsive: Adjust visible slides
function updateResponsive() {
    const width = window.innerWidth;
    if (width < 768) {
        document.querySelectorAll('.category-slide').forEach(slide => {
            slide.style.width = '100%';
        });
    } else if (width < 1024) {
        document.querySelectorAll('.category-slide').forEach(slide => {
            slide.style.width = '50%';
        });
    } else {
        document.querySelectorAll('.category-slide').forEach(slide => {
            slide.style.width = '25%';
        });
    }
}

window.addEventListener('resize', updateResponsive);
updateResponsive();
</script>
<?php endif; ?>

<!-- Featured Products -->
<?php if (!empty($products)): ?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <h2 class="text-4xl md:text-5xl font-bold text-center text-gray-800 mb-16">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($products as $p): ?>
            <div class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2 <?= $p['is_featured'] ? 'featured-product' : '' ?>">
                <div class="aspect-square bg-gray-100 overflow-hidden flex items-center justify-center p-4">
                    <?php if ($p['primary_image']): ?>
                    <img src="uploads/products/<?= htmlspecialchars($p['primary_image']) ?>" alt="<?= htmlspecialchars($p['article_code']) ?>" class="w-full h-full object-contain group-hover:scale-110 transition duration-500">
                    <?php else: ?>
                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <span class="text-sm font-semibold text-red-600 uppercase"><?= htmlspecialchars($p['category_name']) ?></span>
                    <h3 class="text-xl font-bold text-gray-800 mt-2 mb-2">Art. <?= htmlspecialchars($p['article_code']) ?></h3>
                    <?php if ($p['sizes']): ?>
                    <p class="text-gray-600 text-sm mb-4">Sizes: <?= htmlspecialchars($p['sizes']) ?></p>
                    <?php endif; ?>
                    <a href="product.php?slug=<?= $p['slug'] ?>" class="inline-block bg-red-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-red-700 transition">
                        View Details
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-12">
            <a href="products.php" class="inline-block bg-red-600 text-white px-12 py-4 rounded-full text-xl font-bold hover:bg-red-700 transform hover:scale-105 transition shadow-xl">
                View All Products
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-red-600 to-red-800 text-white py-20">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Place Your Order?</h2>
        <p class="text-2xl mb-10 text-red-100">Contact us today for bulk orders and inquiries</p>
        <a href="contact.php" class="inline-block bg-white text-red-600 px-12 py-4 rounded-full text-xl font-bold hover:bg-red-50 transform hover:scale-105 transition shadow-2xl">
            Contact Us Now
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>