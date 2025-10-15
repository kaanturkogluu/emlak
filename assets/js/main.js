
// Header Scroll Effect
window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    const topBar = document.querySelector('.top-bar');
    if (window.scrollY > 40) {
        header.classList.add('scrolled');
        topBar.style.display = 'none';
    } else {
        header.classList.remove('scrolled');
        topBar.style.display = 'block';
    }
});

// Slider Functionality with Creative Transitions
let currentSlideIndex = 0;
let slides = document.querySelectorAll('.slide');
let dots = document.querySelectorAll('.slider-dot');
let isTransitioning = false;

// Slider elementlerini yeniden yükle
function reloadSliderElements() {
    slides = document.querySelectorAll('.slide');
    dots = document.querySelectorAll('.slider-dot');
}

// Efekt dizileri
const exitEffects = ['', 'effect-spiral', 'effect-cube', 'effect-pixelate'];
const entryEffects = ['', 'effect-bounce', 'effect-slidefade'];

function showSlide(index) {
    if (isTransitioning) return;
    
    // Slider elementlerini yeniden yükle
    reloadSliderElements();
    
    if (index >= slides.length) currentSlideIndex = 0;
    if (index < 0) currentSlideIndex = slides.length - 1;

    isTransitioning = true;

    // Rastgele efekt seç
    const randomExitEffect = exitEffects[Math.floor(Math.random() * exitEffects.length)];
    const randomEntryEffect = entryEffects[Math.floor(Math.random() * entryEffects.length)];

    // Yeni slide'ı hemen hazırla (visibility: visible yap ama arkada)
    slides[currentSlideIndex].style.visibility = 'visible';
    slides[currentSlideIndex].style.zIndex = '2';
    
    // Remove active from all slides and add slide-out to current active
    slides.forEach((slide, i) => {
        // Eski efekt sınıflarını temizle
        slide.classList.remove('effect-spiral', 'effect-cube', 'effect-pixelate', 
                               'effect-bounce', 'effect-slidefade');
        
        if (slide.classList.contains('active')) {
            slide.classList.add('slide-out');
            if (randomExitEffect) slide.classList.add(randomExitEffect);
            slide.classList.remove('active');
            slide.style.zIndex = '1'; // Arkaya al
        } else if (i !== currentSlideIndex) {
            slide.classList.remove('slide-out');
            slide.style.visibility = 'hidden';
            slide.style.zIndex = '0';
        }
    });

    dots.forEach(dot => dot.classList.remove('active'));

    // Add active to new slide with creative timing
    setTimeout(() => {
        slides[currentSlideIndex].classList.remove('slide-out');
        if (randomEntryEffect) slides[currentSlideIndex].classList.add(randomEntryEffect);
        slides[currentSlideIndex].classList.add('active');
        dots[currentSlideIndex].classList.add('active');
        
        // Wait for full animation to complete (2s for zoom + 0.5s buffer)
        setTimeout(() => {
            // Clean up old slide-out classes
            slides.forEach(slide => {
                if (!slide.classList.contains('active')) {
                    slide.classList.remove('slide-out');
                    slide.classList.remove('effect-spiral', 'effect-cube', 'effect-pixelate');
                    slide.style.visibility = 'hidden';
                    slide.style.zIndex = '0';
                }
            });
            isTransitioning = false;
        }, 2500);
    }, 50);
}

function changeSlide(direction) {
    if (!isTransitioning) {
        currentSlideIndex += direction;
        showSlide(currentSlideIndex);
    }
}

function currentSlide(index) {
    if (!isTransitioning) {
        currentSlideIndex = index;
        showSlide(currentSlideIndex);
    }
}

// Auto slide with longer intervals for complex animations
let autoSlideInterval;

function startAutoSlide() {
    reloadSliderElements(); // Elementleri yeniden yükle
    if (slides.length > 1) {
        autoSlideInterval = setInterval(() => {
            currentSlideIndex++;
            showSlide(currentSlideIndex);
        }, 7000); // 7 saniye (animasyonlar için daha uzun)
    }
}

// Sayfa yüklendiğinde slider elementlerini yeniden yükle
document.addEventListener('DOMContentLoaded', function() {
    reloadSliderElements();
    
    // Slider sayısına göre otomatik geçişi başlat
    if (typeof window.sliderCount !== 'undefined' && window.sliderCount > 1) {
        startAutoSlide();
    }
});

// Pause auto slide on hover
const heroSlider = document.querySelector('.hero-slider');
if (heroSlider) {
    heroSlider.addEventListener('mouseenter', () => {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    });

    heroSlider.addEventListener('mouseleave', () => {
        if (slides.length > 1) {
            startAutoSlide();
        }
    });
}

// Filter Functionality
const filterBtns = document.querySelectorAll('.filter-btn');
const propertyCards = document.querySelectorAll('.property-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');

        propertyCards.forEach(card => {
            if (filter === 'all' || card.getAttribute('data-category') === filter) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
    });
});

// Search Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchTabs = document.querySelectorAll('.search-tab');
    const transactionTypeInput = document.getElementById('transaction_type');
    
    searchTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            searchTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            // Transaction type input'unu güncelle
            if (transactionTypeInput) {
                const transactionType = tab.getAttribute('data-type');
                transactionTypeInput.value = transactionType;
            }
        });
    });
});


// Contact Button Handler
const contactBtns = document.querySelectorAll('.contact-btn');
contactBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const agentName = btn.parentElement.querySelector('.agent-info span').textContent;
        alert(`${agentName} ile iletişime geçiliyor...\nTelefon: +90 (555) 123 45 67`);
    });
});

// Property Card Click Handler
propertyCards.forEach(card => {
    card.addEventListener('click', (e) => {
        if (!e.target.classList.contains('contact-btn')) {
            const title = card.querySelector('.property-title').textContent;
            console.log(`${title} detayı görüntüleniyor...`);
        }
    });
});

// Smooth Scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Mobile Menu Toggle
const mobileMenu = document.querySelector('.mobile-menu');
const navLinks = document.querySelector('.nav-links');

mobileMenu.addEventListener('click', () => {
    navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
});

// Search Form Handler - Form artık doğrudan ilanlar sayfasına yönlendiriyor
// JavaScript event listener kaldırıldı, form doğrudan submit oluyor

// Vitrin İlanları - SLIDE VERTICAL Effect (Her 3 saniye)
const vitrinImageSliders = document.querySelectorAll('.vitrin-image-slider');

vitrinImageSliders.forEach(slider => {
    const images = slider.querySelectorAll('.vitrin-image');
    let currentImageIndex = 0;
    
    setInterval(() => {
        images[currentImageIndex].classList.add('fade-out');
        images[currentImageIndex].classList.remove('active');
        
        currentImageIndex = (currentImageIndex + 1) % images.length;
        images[currentImageIndex].classList.remove('fade-out');
        images[currentImageIndex].classList.add('active');
    }, 3000);
});

// Vitrin İlanları - Grup Kaydırma
let mainCurrentVitrinGroup = 0;
const vitrinSlider = document.getElementById('vitrinSlider');
const vitrinGroups = document.querySelectorAll('.vitrin-group');
const vitrinDots = document.querySelectorAll('.vitrin-dot');
const totalVitrinGroups = vitrinGroups.length;

function showVitrinGroup(index) {
    if (index >= totalVitrinGroups) {
        mainCurrentVitrinGroup = 0;
    } else if (index < 0) {
        mainCurrentVitrinGroup = totalVitrinGroups - 1;
    } else {
        mainCurrentVitrinGroup = index;
    }

    vitrinSlider.style.transform = `translateX(-${mainCurrentVitrinGroup * 100}%)`;
    
    vitrinDots.forEach((dot, i) => {
        if (i === mainCurrentVitrinGroup) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function goToVitrinGroup(index) {
    showVitrinGroup(index);
}

setInterval(() => {
    mainCurrentVitrinGroup++;
    showVitrinGroup(mainCurrentVitrinGroup);
}, 6000);
