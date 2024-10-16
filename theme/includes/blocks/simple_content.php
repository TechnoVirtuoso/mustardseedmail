<div class="simple_content">
    <div class="simple_button-wrapper">
        <div class="title">
            <?php echo $block["title"] ?>
        </div>
        <div class="simple_content-items">
            <?php foreach ($block["paragraphs"] as $para) { ?>
                <div class="simple_content-item">
                    <?php echo $para["content"] ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>