<div class="content">
    <div class="content-wrapper">
        <?php foreach ($block["section"] as $content_item) { ?>
            <div class="content-item">
                <span class="title">
                    <b><?php echo $content_item["title"] ?>:</b>
                </span>
                <span class="desc">
                    <?php echo $content_item["description"] ?>
                </span>
            </div>
        <?php } ?>
    </div>
</div>