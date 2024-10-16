<section class="quotes section-wrapper">
    <?php foreach ($block["quotes"] as $key => $quote) { ?>
        <div class="quote">
            <div class="icon">
                <svg preserveAspectRatio="xMidYMid meet" data-bbox="40 40 120 120" viewBox="40 40 120 120"
                    xmlns="http://www.w3.org/2000/svg" data-type="color" role="presentation" aria-hidden="true">

                    <g>
                        <path d="M40 160v-45.9c0-40.9 14.8-65 47.1-74.1v25.1c-15.4 6.6-21.5 20.3-21 45.4h21V160H40z"
                            data-color="1"></path>
                        <path d="M112.9 160v-45.9c0-40.9 14.8-65 47.1-74.1v25.1c-15.4 6.6-21.5 20.4-21 45.4h21V160h-47.1z"
                            data-color="1"></path>
                    </g>
                </svg>
            </div>
            <div class="author">
                <?php echo $quote["author"] ?>
            </div>
            <div class="quote-msg">"
                <?php echo $quote["quote"] ?>"
            </div>
        </div>
    <?php } ?>
</section>