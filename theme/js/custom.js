jQuery(document.body).on("click", ".quick-view-popup-btn", function () {
    var id = jQuery(this).data("id");

    // jQuery("body").addClass("popup-show")

    jQuery(".popup-show").show();
    jQuery(".quick-view-popup").empty().show();

    jQuery.ajax({
        url: window.ajaxUrl,
        type: "POST",
        data: {
            action: "quick-view-popup",
            id: id,
        },

        success: function (response) {
            jQuery(".quick-view-popup").replaceWith(response.data.fragment);
            jQuery(".popup-product-images").slick({
                slidesToScroll: 1,
                arrows: false,
                dots: true,
                variableWidth: false,
                infinite: true,
            });
            console.log(response);
        },
        error: function (response) {},
    });
});
function showPopAddToSubComplete() {
    jQuery(".add-to-sub-complete-popup-show").css("display", "grid");
}
function hidePopAddToSubComplete() {
    jQuery(".add-to-sub-complete-popup-show").hide();
    location.reload();
}

function showPopAddToCartComplete() {
    // Show the popup
    jQuery(
        ".add-to-cart-complete-popup-show .add-to-cart-complete-popup-show-wrapper"
    ).css("right", "10px");

    // Hide the popup after 3 seconds
    setTimeout(() => {
        jQuery(
            ".add-to-cart-complete-popup-show .add-to-cart-complete-popup-show-wrapper"
        ).css("right", "-100%");
    }, 3000); // 3000 milliseconds = 3 seconds
}

jQuery(document.body).on("click", ".close-quick-view", function () {
    jQuery(".popup-show").hide();
    jQuery(".quick-view-popup").hide();
    $(".popup-product-images").slick("unslick");
});

function showMoreProducts(button) {
    try {
        var subscription = button.closest(".subscription");
        if (!subscription) {
            throw new Error("Subscription section not found");
        }

        var products = subscription.querySelectorAll(".product");
        for (var i = 3; i < products.length; i++) {
            products[i].style.display = "block";
        }

        var addItem = subscription.querySelector(".add-item");
        if (!addItem) {
            throw new Error("Add item button not found");
        }
        addItem.style.display = "grid";

        var seeMore = subscription.querySelector(".see-more-product");
        if (!seeMore) {
            throw new Error("See more button not found");
        }
        seeMore.style.display = "none";

        var seeLess = subscription.querySelector(".see-less-product");
        if (!seeLess) {
            throw new Error("See less button not found");
        }
        seeLess.style.display = "flex";
    } catch (error) {
        console.error("Error in showMoreProducts:", error.message);
    }
}

function showLessProducts(button) {
    try {
        var subscription = button.closest(".subscription");
        if (!subscription) {
            throw new Error("Subscription section not found");
        }

        var products = subscription.querySelectorAll(".product");
        for (var i = 6; i < products.length; i++) {
            products[i].style.display = "none";
        }

        var addItem = subscription.querySelector(".add-item");
        if (!addItem) {
            throw new Error("Add item button not found");
        }
        addItem.style.display = "none";

        var seeMore = subscription.querySelector(".see-more-product");
        if (!seeMore) {
            throw new Error("See more button not found");
        }
        seeMore.style.display = "flex";

        var seeLess = subscription.querySelector(".see-less-product");
        if (!seeLess) {
            throw new Error("See less button not found");
        }
        seeLess.style.display = "none";
    } catch (error) {
        console.error("Error in showLessProducts:", error.message);
    }
}

// Custom Add to cart
jQuery(function ($) {
    $(document).on("click", ".custom-add-to-cart", function (e) {
        e.preventDefault();

        var $button = $(this);
        var productId = $button.data("product-id");

        // Add the loading class to the button
        $button.addClass("loading");

        // Add the product to the cart using AJAX
        $.ajax({
            url: wc_add_to_cart_params.ajax_url,
            type: "POST",
            data: {
                action: "add_to_cart",
                product_id: productId,
            },
            success: (response) => {
                if (response.error) {
                    console.log(response.error);
                } else {
                    // Open the sidebar cart
                    $(".cart-count").html(response.data.cart_count);
                    $(".cart-form .shop_table").html(
                        response.data.cart_items_html
                    );
                    $(".cart-foot .info .value").html(response.data.total);
                }
            },
            complete: () => {
                // Remove the loading class from the button
                showPopAddToCartComplete();
                $button.removeClass("loading");
            },
        });
    });
});

// Custom Add to subscription toggle
jQuery(function ($) {
    // Toggle subscription content
    $(document).on(
        "click",
        ".add-to-subscription-button .default",
        function (e) {
            var subscriptionContent = $(this).siblings(".possible-buttons");
            subscriptionContent.slideToggle();
            e.stopPropagation(); // Prevent the click event from propagating to the document
        }
    );

    // Close subscription content when clicking outside
    $(document).on("click", function (e) {
        var subscriptionButton = $(".add-to-subscription-button .default");
        var subscriptionContent =
            subscriptionButton.siblings(".possible-buttons");

        // Check if the clicked element is not part of the current button or its content
        if (
            !subscriptionButton.is(e.target) &&
            subscriptionButton.has(e.target).length === 0 &&
            !subscriptionContent.is(e.target) &&
            subscriptionContent.has(e.target).length === 0
        ) {
            subscriptionContent.slideUp();
        }
    });
});

// Custom Add Product to Subscription Meta
jQuery(function ($) {
    $(document).on(
        "click",
        ".add-to-subscription-button .possible-buttons .subscription",
        function (e) {
            let sub = $(this).attr("sub-id");
            let prod = $(this).attr("prod-id");
            // Make an AJAX request to the API endpoint
            $.ajax({
                url: wc_add_to_cart_params.ajax_url,
                type: "POST",
                data: {
                    action: "add_product_to_subscription",
                    product_id: prod,
                    subscription_id: sub,
                },
                success: function (response) {
                    // Should show popup
                    showPopAddToSubComplete();
                },
                error: function (xhr, status, error) {
                    console.error(
                        "Request failed with status " +
                            xhr.status +
                            ": " +
                            error
                    );
                },
            });
        }
    );
});

// Collection Carousel Block
$(document).ready(() => {
    $(".collection-carousel .carousel").slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        prevArrow: `
            <button class="leftButton">
                <svg width="12px" height="24px" viewBox="0 0 12 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    style="pointer-events: none;">
                    <title>09 Icons / Arrows / Navigation / RegularArrow / Medium / Left / CenterAlign</title>
                    <g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="black" fill-rule="evenodd">
                        <g id="slider-gallery---arrows" transform="translate(-57.000000, -247.000000)" class="tI157n">
                            <g id="🎨-Color" transform="translate(51.000000, 247.000000)">
                                <polygon
                                    transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(90.000000) translate(-12.000000, -12.000000) "
                                    points="11.9989984 6 0 16.4818792 1.31408063 18 11.9989984 8.66778523 22.6859194 18 24 16.4818792">
                                </polygon>
                            </g>
                        </g>
                    </g>
                </svg>
            </button>
        `,
        nextArrow: `
            <button class="rightButton" style="rotate: 180deg">
                <svg width="12px" height="24px" viewBox="0 0 12 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    style="pointer-events: none;">
                    <title>09 Icons / Arrows / Navigation / RegularArrow / Medium / Left / CenterAlign</title>
                    <g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="black" fill-rule="evenodd">
                        <g id="slider-gallery---arrows" transform="translate(-57.000000, -247.000000)" class="tI157n">
                            <g id="🎨-Color" transform="translate(51.000000, 247.000000)">
                                <polygon
                                    transform="translate(12.000000, 12.000000) scale(-1, 1) rotate(90.000000) translate(-12.000000, -12.000000) "
                                    points="11.9989984 6 0 16.4818792 1.31408063 18 11.9989984 8.66778523 22.6859194 18 24 16.4818792">
                                </polygon>
                            </g>
                        </g>
                    </g>
                </svg>
            </button>
        `,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 786,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });
});

$(document).ready(function () {
    $(document).on("click", ".image-open", function (e) {
        // Prevent default behavior
        e.preventDefault();

        // Get the src value of the clicked image
        const src = $(this).attr("src");

        // Log src value for debugging
        console.log("Image source:", src);

        // Set the src attribute of the image in the popup
        $(".image-zoom-popup img").attr("src", src);

        // Display the popup
        $(".image-zoom-popup").css("display", "flex");

        document.documentElement.style.overflowY = 'hidden';
    });

    $(document).on("click", ".image-zoom-popup button", function (e) {
        // Prevent default behavior
        e.preventDefault();

        // Hide the popup if the click was outside the ".image-zoom-popup"
        $(".image-zoom-popup").css("display", "none");

        document.documentElement.style.overflowY = '';
    });
});
