document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');

    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            nav.classList.toggle('open');
        });
    }

    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            const password = registerForm.querySelector('input[name="password"]');
            const confirm  = registerForm.querySelector('input[name="confirm_password"]');
            if (password.value !== confirm.value) {
                event.preventDefault();
                alert('Lozinke se moraju podudarati.');
            }
        });
    }

    // Cart modal closing
    (function () {
        const modal = document.getElementById('cartModal');
        const continueBtn = document.getElementById('continueShoppingBtn');
        if (!modal) return;

        if (continueBtn) {
            continueBtn.addEventListener('click', function () {
                modal.classList.add('cart-modal-closing');
                setTimeout(function () {
                    modal.remove();
                    // Idi na prethodnu stranicu (odakle je korisnik došao na product.php)
                    const saved = sessionStorage.getItem('productReferrer');
                    if (saved) {
                        window.location.href = saved;
                    }
                }, 250);
            });
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('cart-modal-closing');
                setTimeout(function () {
                    modal.remove();
                    const saved = sessionStorage.getItem('productReferrer');
                    if (saved) window.location.href = saved;
                }, 250);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                modal.classList.add('cart-modal-closing');
                setTimeout(function () { modal.remove(); }, 250);
            }
        });
    })();

    // Image preview for admin product form (moved from inline admin_product_form.php)
    (function () {
        const input = document.getElementById('image-url-input');
        const wrap  = document.getElementById('img-preview-wrap');
        const img   = document.getElementById('img-preview');
        if (!input || !img) return;
        input.addEventListener('input', function () {
            const url = this.value.trim();
            img.src = url;
            wrap.style.display = url ? '' : 'none';
        });
    })();

    // Back button + spremi referrer za "Nastavi kupovinu"
    (function () {
        const btn = document.getElementById('back-btn');
        if (!btn) return;
        const ref = document.referrer;

        // Spremi referrer samo ako nije sam product.php (tj. nije POST reload)
        if (ref && ref.indexOf('product.php') === -1) {
            sessionStorage.setItem('productReferrer', ref);
        }

        const saved = sessionStorage.getItem('productReferrer');
        btn.href = saved || ref || 'products.php';
    })();

});