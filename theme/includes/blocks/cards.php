<section class="cards">
    <div class="head">
        <div class="title">
            <?php echo $block["main_title"] ?>
        </div>
        <div class="desc">
            <?php echo $block["main_description"] ?>
        </div>
    </div>
    <div class="cards-wrapper section-wrapper">
        <?php foreach ($block["cards"] as $key => $card) { ?>
            <div class="card">
                <div class="image">
                    <img src="<?php echo $card["image"]["url"] ?>" alt="">
                </div>
                <div class="title"><?php echo $key + 1?>.
                    <?php echo $card["title"] ?>
                </div>
                <div class="description">
                    <?php echo $card["description"] ?>
                </div>
            </div>
        <?php } ?>
    </div>
</section>