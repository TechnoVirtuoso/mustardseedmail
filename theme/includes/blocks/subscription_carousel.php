<section class="subscription_carousel" id="subscription-section">
  <div class="subscriptions">
    <div class="title"><?php echo $block["title"] ?></div>
    <div class="subscriptions-wrapper">
      <?php foreach ($block["subscriptions"] as $prod) {
        $product = wc_get_product($prod); ?>
        <div class="subscription">
          <div class="image">
            <span class="tag">Subscription</span>
            <a href="<?php echo $product->get_permalink() ?>">
              <img src="<?php echo wp_get_attachment_url($product->get_image_id()); ?>" alt="">
            </a>
            <div class="quickView quick-view-popup-btn" data-id="<?php echo $prod ?>">
              <span>Quick View</span>
            </div>
          </div>
          <div class="sub-content">
            <a class="name" href="<?php echo $product->get_permalink() ?>">
              <?php echo $product->get_name(); ?>
            </a>
            <a class="price" href="<?php echo $product->get_permalink() ?>">
              $
              <?php echo $product->get_price(); ?>
            </a>
            <button class="custom-add-to-cart" data-product-id="<?php echo $prod; ?>">
              <span class="simple">Add to Cart</span>
              <span class="load">Loading</span>
            </button>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>

</section>
<script>
  $(document).ready(() => {
    $('.subscription_carousel .subscriptions-wrapper').slick({
      infinite: true,
      slidesToShow: 2,
      slidesToScroll: 1,
      prevArrow: `
      <button class="leftButton">
        <svg width="12px" height="24px" viewBox="0 0 12 24" version="1.1" xmlns="http://www.w3.org/2000/svg"
          style="pointer-events: none;">
          <title>09 Icons / Arrows / Navigation / RegularArrow / Medium / Left / CenterAlign</title>
          <g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
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
          <g id="Slider-Gallery-" stroke="none" stroke-width="1" fill="white" fill-rule="evenodd">
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
          }
        },
        {
          breakpoint: 991,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 786,
          settings: {
            slidesToShow: 1,
          }
        }

      ]

    });
  })
</script>