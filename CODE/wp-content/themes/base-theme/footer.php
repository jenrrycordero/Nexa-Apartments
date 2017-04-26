    </div><!-- nexa-app-wrapper-->
</div><!-- #content-wrapper -->
<?php
if (get_field("display_back_to_top", "option"))
    render_back_top();

the_field('custom_js_footer', 'option');
gravity_form_enqueue_scripts(1, true);

wp_footer(); ?>
</body>
</html>