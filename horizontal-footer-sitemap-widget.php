<?php
/*
Plugin Name: Horizontal Footer Sitemap - Widget
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A simple widget which uses the menu of your selection to show them horizontaly as a Horizontal Footer Sitemap
Version: 1.0.1
Author: Mario Shtika
Author URI: http://mario.shtika.info
License: GPL2
*/


/* Start Adding Functions Below this Line */

// Creating the widget 
class horizontal_footer_sitemap_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'horizontal_footer_sitemap_widget',
            // Widget name will appear in UI
            __('Horizontal Footer Sitemap', 'horizontal_footer_sitemap_widget'),
            // Widget description
            array('description' => __('Add a custom menu to your side bar and show it horizontaly as a Horizontal Footer Sitemap', 'horizontal_footer_sitemap_widget'),)
        );

        // Register style sheet.
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
    }

    // Widget Frontend
    public function widget($args, $instance)
    {
        // Get menu
        $nav_menu = !empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;

        if (!$nav_menu) {
            return;
        }

        echo '<div id="footer_sitemap_widget">';
        echo $args['before_widget'];

        wp_nav_menu(array('fallback_cb' => '', 'menu' => $nav_menu));

        echo $args['after_widget'];
        echo '</div>';
    }

    public function register_plugin_styles()
    {
        wp_register_style('horizontal_footer_sitemap_widget', plugins_url('/css/widget.css', __FILE__));

        wp_enqueue_style('horizontal_footer_sitemap_widget');
    }

    // Widget Backend 
    public function form($instance)
    {
        $nav_menu = isset($instance['nav_menu']) ? $instance['nav_menu'] : '';

        // Get menus
        $menus = wp_get_nav_menus(array('orderby' => 'name'));

        // If no menus exists, direct the user to go and create some.
        if (!$menus) {
            echo '<p>' . sprintf(__('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php')) . '</p>';
            return;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
            <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
                <option value="0"><?php _e('&mdash; Select &mdash;') ?></option>
                <?php
                foreach ($menus as $menu) {
                    echo '<option value="' . $menu->term_id . '"'
                        . selected($nav_menu, $menu->term_id, false)
                        . '>' . esc_html($menu->name) . '</option>';
                }
                ?>
            </select>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        if (!empty($new_instance['nav_menu'])) {
            $instance['nav_menu'] = (int) $new_instance['nav_menu'];
        }
        return $instance;
    }
}

// Class horizontal_footer_sitemap_widget ends here
// Register and load the widget
function horizontal_footer_sitemap_widget_load_widget()
{
    register_widget('horizontal_footer_sitemap_widget');
}

add_action('widgets_init', 'horizontal_footer_sitemap_widget_load_widget');

/* Stop Adding Functions Below this Line */
?>