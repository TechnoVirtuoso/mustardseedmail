<div class="accordion_with_card" style="background-image: url('<?php echo $block["background"]["url"] ?>')">
    <div class="main-title">
        <?php echo $block["title"] ?>
    </div>
    <div class="accordion">
        <?php foreach ($block["accordion"] as $accordion_item) { ?>
            <div class="item">
                <div class="title">
                    <span>
                        <?php echo $accordion_item["title"] ?>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path class="arrowDown"
                            d="M8.14644661,10.1464466 C8.34170876,9.95118446 8.65829124,9.95118446 8.85355339,10.1464466 L12.4989857,13.7981758 L16.1502401,10.1464466 C16.3455022,9.95118446 16.6620847,9.95118446 16.8573469,10.1464466 C17.052609,10.3417088 17.052609,10.6582912 16.8573469,10.8535534 L12.4989857,15.2123894 L8.14644661,10.8535534 C7.95118446,10.6582912 7.95118446,10.3417088 8.14644661,10.1464466 Z">
                        </path>
                    </svg>
                </div>
                <div class="description">
                    <?php echo $accordion_item["description"] ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="accordion-card">
        <div class="image">
            <img src="<?php echo $block["card"]["image"]["url"] ?>" alt="">
        </div>
        <div class="card-content">
            <div class="wrapper">
                <div class="title">
                    <?php echo $block["card"]["title"] ?>
                </div>
                <div class="desc">
                    <?php echo $block["card"]["description"] ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    jQuery(document).ready(function () {
        // Close all descriptions initially
        jQuery(".accordion .item .description").hide();

        // Open the first item by default
        // jQuery(".accordion .item:first-child .description").slideDown(function () {
        //     jQuery(this).parent().addClass("active");
        // });

        // Click event handler
        jQuery(".accordion .item").click(function () {
            const clickedDescription = jQuery(this).children(".description");

            if (clickedDescription.is(":visible")) {
                clickedDescription.slideUp(function () {
                    jQuery(this).parent().removeClass("active");
                });
            } else {
                jQuery(".accordion .item .description").slideUp(function () {
                    jQuery(this).parent().removeClass("active");
                });
                clickedDescription.slideDown(function () {
                    jQuery(this).parent().addClass("active");
                });
            }
        });
    });

</script>