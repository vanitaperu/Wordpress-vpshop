(e=>{"use strict";e.ModuleTestimonial=class extends e.Module{constructor(e){super(e)}static getOptions(){return[{id:"mod_title_testimonial",type:"title"},{id:"layout_testimonial",type:"layout",label:"lay",mode:"sprite",post_grid:!0,control:{classSelector:".builder-posts-wrap"}},{type:"query_posts",term_id:"category_testimonial",slug_id:"query_slug_testimonial",taxonomy:"testimonial-category"},{id:"post_per_page_testimonial",type:"number",label:"npost",help:"nposth"},{id:"offset_testimonial",type:"number",label:"ofs",help:"ofsh"},{id:"order_testimonial",type:"select",label:"order",help:"shelp",order:!0},{id:"orderby_testimonial",type:"orderby_post",binding:{select:{hide:"meta_key_testimonial"},meta_value:{show:"meta_key_testimonial"},meta_value_num:{show:"meta_key_testimonial"}}},{id:"meta_key_testimonial",type:"text",label:"cfieldk"},{id:"display_testimonial",type:"select",label:"disp",options:{content:"content",excerpt:"excerpt",none:"none"}},{id:"hide_feat_img_testimonial",type:"toggle_switch",label:"fimg",binding:{checked:{show:["image_size_testimonial","img_width_testimonial","img_height_testimonial"]},not_checked:{hide:["image_size_testimonial","img_width_testimonial","img_height_testimonial"]}}},{id:"image_size_testimonial",type:"image_size"},{id:"img_width_testimonial",type:"number",label:"imgw"},{id:"img_height_testimonial",type:"number",label:"imgh"},{id:"hide_post_title_testimonial",type:"toggle_switch",label:"ptitle"},{id:"hide_page_nav_testimonial",type:"toggle_switch",label:"pagin"},{type:"custom_css_id",custom_css:"css_testimonial"}]}}})(tb_app);