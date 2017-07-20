<?php

class Mapado_Detail_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('mapado_detail_widget', 'Mapado Event Details', array('description' => 'Event Details are moved to a sidebar widget'));
    }

    public function widget($args, $instance)
    {
        global $mapado;

        /* in MapadoPublicAuth Class */
        $mapado->eventDetailWidget();
    }
}


require_once MAPADO_PLUGIN_PATH . 'class/widget_default_template.php';

class Mapado_Event_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct('mapado_event_widget', 'Mapado Event Listing', array('description' => 'A tiny event listing featuring next events'));
    }


    public function form($instance)
    {
        // Check values
        global $widget_card_template_default;
        global $mapado;

        $imported_lists = $mapado->imported_lists;

        if ($instance) {
            $title = esc_attr($instance['title']);
            $widget_template = $instance['widget_template'];
            $nb_events = $instance['nb_events'];
            $thumbnail_width = $instance['thumbnail_width'];
            $thumbnail_height = $instance['thumbnail_height'];
            $list_uuid = $instance['list_uuid'];
            $list_depth = $instance['list_depth'];
        } else {
            $title = '';
            $nb_events = 3;
            $thumbnail_width = 300;
            $thumbnail_height = 200;
        }
        if (empty($widget_template)) {
            $widget_template = $widget_card_template_default;
        } ?>
            <script type="text/javascript">
                 function updateWidthHeight(input_field_id, field_to_update_id, ratio) {
                    document.getElementById(field_to_update_id).value = Math.round(document.getElementById(input_field_id).value / ratio)
                 }
            </script>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'wp_widget_plugin'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('list_uuid'); ?>"><?php _e('List to display', 'wp_widget_plugin'); ?></label>
                <select name="<?php echo $this->get_field_name('list_uuid'); ?>">
                <?php
                var_dump($list_uuid);
        foreach ($imported_lists as $uuid => $list_name) {
            echo '<option value="'.$uuid.'" '.($list_uuid == $uuid ? "selected" : "").'>'.$list_name.'</option>';
        } ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('list_depth'); ?>"><?php _e('List depth', 'wp_widget_plugin'); ?></label>
                <select name="<?php echo $this->get_field_name('list_depth'); ?>">
                <option value="1" <?php if ($list_depth == 1) {
            echo "selected";
        } ?> >1</option>
                <option value="2" <?php if ($list_depth == 2) {
            echo "selected";
        } ?> >2</option>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('nb_events'); ?>"><?php _e('Number of events displayed', 'wp_widget_plugin'); ?></label>
                <input id="<?php echo $this->get_field_id('nb_events'); ?>" name="<?php echo $this->get_field_name('nb_events'); ?>" type="text" value="<?php echo $nb_events; ?>" size = 5 />
            </p>
            <p>
                <label ><?php _e('Thumbnails dimensions', 'wp_widget_plugin'); ?></label>
                <input id="<?php echo $this->get_field_id('thumbnail_width'); ?>" name="<?php echo $this->get_field_name('thumbnail_width'); ?>" type="text" value="<?php echo $thumbnail_width; ?>" size = 5 onblur="updateWidthHeight('<?php echo $this->get_field_id('thumbnail_width'); ?>', '<?php echo $this->get_field_id('thumbnail_height'); ?>', 300/200)" />
                x
                <input id="<?php echo $this->get_field_id('thumbnail_height'); ?>" name="<?php echo $this->get_field_name('thumbnail_height'); ?>" type="text" value="<?php echo $thumbnail_height; ?>" size = 5 onblur="updateWidthHeight('<?php echo $this->get_field_id('thumbnail_height'); ?>', '<?php echo $this->get_field_id('thumbnail_width'); ?>', 200/300)" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widget_template'); ?>"><?php _e('Single Event Template', 'wp_widget_plugin'); ?></label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('widget_template'); ?>" name="<?php echo $this->get_field_name('widget_template'); ?>" rows="7" cols="20" ><?php echo $widget_template; ?></textarea>
            </p>
        <?php

    }

    public function update($new_instance, $old_instance)
    {
        global $widget_card_template_default;

        $instance = $old_instance;

        // Fields
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['list_uuid'] = $new_instance['list_uuid'];
        $instance['list_depth'] = $new_instance['list_depth'];
        if ($instance['list_depth'] != 2) {
            $instance['list_depth'] = 1;
        }
        $instance['widget_template'] = $new_instance['widget_template'];
        if (empty($instance['widget_template'])) {
            $instance['widget_template'] = $widget_card_template_default;
        }
        $instance['nb_events'] = strip_tags($new_instance['nb_events']);
        if ($instance['nb_events'] < 1 or $instance['nb_events'] > 15) {
            $instance['nb_events'] = 3;
        }
        $instance['thumbnail_width'] = $new_instance['thumbnail_width'];
        if ($instance['thumbnail_width'] < 20 or $instance['thumbnail_width'] > 300) {
            $instance['thumbnail_width'] = 300;
        }
        $instance['thumbnail_height'] = $new_instance['thumbnail_height'];
        if ($instance['thumbnail_height'] < 20 or $instance['thumbnail_height'] > 300) {
            $instance['thumbnail_height'] = 200;
        }
        return $instance;
    }


    public function widget($args, $instance)
    {
        global $mapado;
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'] ;
        }
        /* in MapadoPublicAuth Class */
        $mapado->eventListingWidget($instance['widget_template'], $instance['thumbnail_width'], $instance['thumbnail_height'], $instance['list_uuid'], $instance['nb_events'], $instance['list_depth']);
    }
}
