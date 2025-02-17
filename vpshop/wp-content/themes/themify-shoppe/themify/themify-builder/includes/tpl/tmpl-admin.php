<?php
global $post;
if (!is_object($post))
    return;
?>
<template id="tmpl-builder_admin_canvas_block">
    <div class="themify_builder_content-<?php echo $post->ID; ?> themify_builder themify_builder_admin tf_rel tf_clearfix">
        <div class="tb_row_panel tf_box tf_rel tf_clearfix">
            <div id="tb_row_wrapper">
                <div data-postid="<?php echo $post->ID; ?>" class="tb_active_builder"></div>
            </div>
        </div>
    </div>
</template>
<div class="tb_fixed_scroll" id="tb_fixed_bottom_scroll"></div>
<div class="tb_loader tf_loader tf_abs_c tf_box tf_hide"></div>
<style id="tf_lazy_common">
    img{max-width:100%;height:auto}.tf_fa{display:inline-block;width:1em;height:1em;stroke-width:0;stroke:currentColor;overflow:visible;fill:currentColor;pointer-events:none;vertical-align:middle}#tf_svg symbol{overflow:visible}.tf_lazy{position:relative;visibility:visible;display:block;opacity:.3}
</style>