// main.js – globalna JS logika koja se izvodi na svim stranicama
// Učitava se u header.php s "defer" atributom (izvodi se nakon parsiranja HTML-a)

document.addEventListener('DOMContentLoaded', () => {

    // ── Mobilna navigacija ────────────────────────────────────────────────
    // Hamburger gumb togglea klasu "open" na nav elementu (CSS skriva/prikazuje)
    const navToggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-nav]');

    if (navToggle && nav) {
        navToggle.addEventListener('click', () => {
            nav.classList.toggle('open');
        });
    }

    // ── Validacija forme za registraciju ─────────────────────────────────
    // Client-side provjera podudaranja lozinki (PHP radi i server-side provjeru)
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (event) => {
            const password = registerForm.querySelector('input[name="password"]');
            const confirm  = registerForm.querySelector('input[name="confirm_password"]');
            if (password.value !== confirm.value) {
                event.preventDefault(); // Zaustavlja slanje forme
                alert('Lozinke se moraju podudarati.');
            }
        });
    }

    // ── Cart modal (potvrda dodavanja u košaricu) ─────────────────────────
    // Modal se renderira server-side u product.php kad je $addedToCart = true
    (function () {
        const modal = document.getElementById('cartModal');
        const continueBtn = document.getElementById('continueShoppingBtn');
        if (!modal) return; // Modal ne postoji na ovoj stranici – izlazimo

        // Gumb "Nastavi kupovinu" – animirano zatvaranje + povratak na prethodnu stranicu
        if (continueBtn) {
            continueBtn.addEventListener('click', function () {
                modal.classList.add('cart-modal-closing');
                setTimeout(function () {
                    modal.remove();
                    // Vraćamo se na stranicu s koje je korisnik došao (products.php ili slično)
                    const saved = sessionStorage.getItem('productReferrer');
                    if (saved) {
                        window.location.href = saved;
                    }
                }, 250); // 250ms = trajanje CSS animacije zatvaranja
            });
        }

        // Klik izvan modala (na overlay) – zatvara modal na isti način
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

        // Tipka ESC – zatvara modal (pristupačnost)
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                modal.classList.add('cart-modal-closing');
                setTimeout(function () { modal.remove(); }, 250);
            }
        });
    })();

    // ── Live preview slike u admin formi ─────────────────────────────────
    // Kad admin upiše URL slike, odmah se prikazuje preview ispod inputa
    (function () {
        const input = document.getElementById('image-url-input');
        const wrap  = document.getElementById('img-preview-wrap');
        const img   = document.getElementById('img-preview');
        if (!input || !img) return; // Nismo na admin_product_form.php stranici
        input.addEventListener('input', function () {
            const url = this.value.trim();
            img.src = url;
            wrap.style.display = url ? '' : 'none'; // Sakrivamo wrap ako je URL prazan
        });
    })();

    // ── Back button na stranici proizvoda ────────────────────────────────
    // Gumb "Povratak na proizvode" treba ići na stranicu odakle je korisnik došao
    // Koristimo sessionStorage jer document.referrer ne radi kod POST requesta
    (function () {
        const btn = document.getElementById('back-btn');
        if (!btn) return; // Nismo na product.php stranici

        const ref = document.referrer;

        // Pamtimo referrer samo ako dolazimo s neke druge stranice (ne s POST reloada product.php)
        if (ref && ref.indexOf('product.php') === -1) {
            sessionStorage.setItem('productReferrer', ref);
        }

        // Postavljamo href gumba – fallback na products.php ako nema zapamćene stranice
        const saved = sessionStorage.getItem('productReferrer');
        btn.href = saved || ref || 'products.php';
    })();

});