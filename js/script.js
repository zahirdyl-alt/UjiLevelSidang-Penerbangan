document.addEventListener('DOMContentLoaded', () => {
    // Hamburger Menu Logic
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');

    hamburgerBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!hamburgerBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });

    // Smooth scroll offset for navbar
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                // Adjust the scroll position by subtracting navbar height (~70px)
                const headerOffset = 80;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
          
                window.scrollTo({
                     top: offsetPosition,
                     behavior: "smooth"
                });

                // Update active class on nav links
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                if (this.classList.contains('nav-item')) {
                    this.classList.add('active');
                }
            }
        });
    });

    // Navbar blur on scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    });

    // Fake reCAPTCHA Logic
    const rcWidget = document.getElementById('rc-checkbox');
    const hiddenCheckbox = document.getElementById('robot');
    const submitBtn = document.querySelector('.submit-btn');

    if (rcWidget) {
        // Awalnya disable tombol kirim
        if(submitBtn && !hiddenCheckbox.checked) {
            submitBtn.style.opacity = '0.5';
            submitBtn.style.pointerEvents = 'none';
        }

        rcWidget.addEventListener('click', function() {
            if (this.classList.contains('checked') || this.classList.contains('loading')) return;

            // Start loading
            this.classList.add('loading');
            
            // Wait 1.5 seconds for fake validation
            setTimeout(() => {
                this.classList.remove('loading');
                this.classList.add('checked');
                // Check the hidden input to allow form submission
                hiddenCheckbox.checked = true;
                
                // Enable submit button
                if(submitBtn) {
                    submitBtn.style.opacity = '1';
                    submitBtn.style.pointerEvents = 'auto';
                }
            }, 1500);
        });
    }
});
