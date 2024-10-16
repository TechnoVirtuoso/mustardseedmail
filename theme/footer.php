<?php

?>

</div><!-- closing all div -->
<?php
if (
    !(is_page('cart') || is_cart())
) {
    ?>
    <footer>
        <div class="footer-wrapper">
            <div class="buttons">
                <a href="/#subscription-section">Shop</a>
                <a href="/">Home</a>
                <a href="/blogs">Blog</a>
            </div>
            <div class="copyright">
                © 2024 Mustard Seed Mail. All rights reserved.
            </div>
        </div>
    </footer>
    <?php
}
?>
<div class="popup-show" style="display:none"></div>

<div class="add-to-sub-complete-popup-show">
    <div class="add-to-sub-complete-popup-show-wrapper">
        <div class="title">Added To Subscription</div>
        <button onclick="hidePopAddToSubComplete()">X</button>
    </div>
</div>

<div class="add-to-cart-complete-popup-show">
    <div class="add-to-cart-complete-popup-show-wrapper">
        <div class="title">Added To Cart</div>
    </div>
</div>

<div class="quick-view-popup" style="display:none"></div>
<div class="test">
    <?php process_all_users_subscriptions_activation_email(); ?>
</div>

<!-- Scroll to top button -->
<div id="scrollToTopBtn" onclick="scrollToTop()">
    <svg class="svg-icon" viewBox="0 0 20 20">
        <path
            d="M13.889,11.611c-0.17,0.17-0.443,0.17-0.612,0l-3.189-3.187l-3.363,3.36c-0.171,0.171-0.441,0.171-0.612,0c-0.172-0.169-0.172-0.443,0-0.611l3.667-3.669c0.17-0.17,0.445-0.172,0.614,0l3.496,3.493C14.058,11.167,14.061,11.443,13.889,11.611 M18.25,10c0,4.558-3.693,8.25-8.25,8.25c-4.557,0-8.25-3.692-8.25-8.25c0-4.557,3.693-8.25,8.25-8.25C14.557,1.75,18.25,5.443,18.25,10 M17.383,10c0-4.07-3.312-7.382-7.383-7.382S2.618,5.93,2.618,10S5.93,17.381,10,17.381S17.383,14.07,17.383,10">
        </path>
    </svg>
</div>

<div class="image-zoom-popup" style="display: none;">
    <div class="image-wrapper">
        <button>X</button>
        <div class="image">
            <img src-="">
        </div>
    </div>
</div>

<?php wp_footer(); ?>

</body>

<script>
    // Show or hide the button based on scroll position
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        var scrollToTopBtn = document.getElementById("scrollToTopBtn");
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            scrollToTopBtn.style.display = "flex";
        } else {
            scrollToTopBtn.style.display = "none";
        }
    }

    // Function to scroll to the top of the page
    function scrollToTop() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    }
</script>


</html>