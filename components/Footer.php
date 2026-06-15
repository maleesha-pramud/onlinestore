<footer class="main-footer py-5 mt-5 border-top">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <a href="/index.php" class="footer-brand d-block mb-3">
                    <img src="/assets/images/logo/logo-only-text.png" alt="2nd Home" style="height: 40px;">
                </a>
                <p class="text-secondary">Experience the comfort of a second home, wherever you go. We provide the best properties at the most affordable prices.</p>
                <div class="social-links d-flex gap-3">
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-secondary fs-5"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Support</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="#" class="text-secondary d-block mb-2">Help Center</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">AirCover</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Anti-discrimination</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Disability support</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Cancellation options</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-4">Hosting</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="/signup.php" class="text-secondary d-block mb-2">2nd Home your home</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">AirCover for Hosts</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Hosting resources</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Community forum</a></li>
                    <li><a href="#" class="text-secondary d-block mb-2">Hosting responsibly</a></li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-4">Newsletter</h5>
                <p class="text-secondary mb-3">Subscribe to our newsletter for the latest deals and updates.</p>
                <form class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="footer-bottom mt-5 pt-4 border-top text-center text-secondary">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> 2nd Home. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
.footer-links a {
    text-decoration: none;
    transition: color 0.3s ease;
}
.footer-links a:hover {
    color: var(--primary-accent) !important;
}
.social-links a:hover {
    color: var(--primary-accent) !important;
}
</style>