<?php
/*
Plugin Name: Anime Dropdown Widget
Plugin URI: http://anime2.kokidokom.net/a-tags-dropdown-menu-for-anime/
Description: The widgets shows all tags that are anime titles as a dropdown menu.
Author: Eugen Rochko
Version: 2.0
Author URI: http://anime2.kokidokom.net/
Text Domain: adw
*/

function adw_dropdown() {
    $get_tags = get_terms('anime', 'order_by=name');
    $dropdown = '';
    $dropdown_start = '<select id="select_anime" onchange="document.location.href=this.options[this.selectedIndex].value;" name="select_anime">';
    $dropdown_start .= sprintf('<option>%s</option>', __('Select anime', 'adw'));
    foreach($get_tags as $tag):
        $dropdown .= sprintf('<option value="%1$s">%2$s</option>', get_term_link($tag->slug, 'anime'), $tag->name);
    endforeach;
    $dropdown_end = '</select>';
    echo $dropdown_start.$dropdown.$dropdown_end;
}

function adw_init_taxonomy() {
    register_taxonomy( 'anime', 'post', array( 'hierarchical' => false, 'label' => __('Anime', 'adw'), 'query_var' => true, 'rewrite' => true ) );
}

class adw_widget extends WP_Widget {
    /** constructor */
    function adw_widget() {
        parent::WP_Widget(false, $name = 'ADW Select Anime');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        ?>
              <?php echo $before_widget; ?>
                  <?php echo $before_title
                      . $instance['title']
                      . $after_title; ?>
                  <?php adw_dropdown(); ?>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        $title = esc_attr($instance['title']);
        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
        <?php 
    }

}

add_action('init', 'adw_init_taxonomy', 0);
add_action('widgets_init', create_function('', 'return register_widget("adw_widget");'));

?>