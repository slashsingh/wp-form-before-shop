<form id="wcfbo-form" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" method="POST">
    <?php wcfbo_render_form_fields(); ?>
    <input type="hidden" name="wcfbo_form_submission" value="1">
    <button type="submit">Continue to Shop</button>
</form>