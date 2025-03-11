<form id="wcfbo-form" class="wcfbo-form" action="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" method="POST">
    <?php wcfbo_render_form_fields(); ?>
    <input type="hidden" name="wcfbo_form_submission" value="1">
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Submit">
    </div>
</form>