<?php

namespace OBVQP_WpPluginAutoload\Widgets;

use OBVQP_WpPluginAutoload\Core\OBVQP_Config;
use OBVQP_WpPluginAutoload\Core\OBVQP_Results;
/**
 * Widget API: WP_Onionbuzz_Leaderboard class
 *
 * @package Onionbuzz
 * @subpackage Widgets
 * @since 1.0.0
 */

class WP_Onionbuzz_Leaderboard extends \WP_Widget {

    private $configs;

    /**
     * Sets up a new Leaderboard widget instance.
     */
    public function __construct() {

        $oConfig = new OBVQP_Config();
        $this->configs = $oConfig->get();

        $widget_ops = array(
            'classname' => 'widget_onionbuzz_leaderboard',
            'description' => __( 'Onionbuzz Leaderboard' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct( 'widget_onionbuzz_leaderboard', __( 'Onionbuzz Leaderboard' ), $widget_ops );
    }

    /**
     * Outputs the content for the current Leaderboard widget instance.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Leaderboard widget instance.
     */
    public function widget( $args, $instance ) {
        static $first_dropdown = true;

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Leaderboard' ) : $instance['title'], $instance, $this->id_base );

        echo $args['before_widget'];
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        $onlyregistered = ! empty( $instance['dropdown'] ) ? '1' : '0';
        $limit = ! empty( $instance['number'] ) ? $instance['number'] : '5';

        $oResults = new OBVQP_Results();
        $players_array = $oResults->getLeaderboardPlayers($onlyregistered, $limit);

        $place = 1;
        ?>
        <div class="laqm-leaderboard">
        <?php
        $total_p = count($players_array);
        foreach ($players_array as $k=>$v){
            $user_info[$k] = get_userdata($players_array[$k]['user_id']);
            if(!$user_info[$k]->first_name && !$user_info[$k]->last_name){
                $players_array[$k]['user_name'] = "Anonymous";
            }
            else{
                $players_array[$k]['user_name'] = $user_info[$k]->first_name.' '.$user_info[$k]->last_name;
            }
            ?>
            <div class="laqm-player-info <?php if(($k+1) == $total_p){ echo 'last-player';} ?>">
                <div class="laqm-player-place">
                    <?=$place?>.
                </div>
                <div class="laqm-player-avatar">
                    <img src="<?=get_avatar_url( $players_array[$k]['user_id'], array('size' => 40))?>">
                </div>
                <div class="laqm-player-name">
                    <?=$players_array[$k]['user_name']?>
                </div>
                <div class="laqm-player-score">
                    <?=$players_array[$k]['sumpoints']?>
                </div>
            </div>
            <div style="clear: both;"></div>
            <?php
            $place++;
        }
        ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Handles updating settings for the current Leaderboard widget instance.
     *
     * @since 1.0.0
     * @access public
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = !empty($new_instance['number']) ? $new_instance['number'] : '';
        $instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

        return $instance;
    }

    public function form( $instance ) {
        //Defaults
        $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
        $title = sanitize_text_field( $instance['title'] );
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" placeholder="<?php esc_attr_e( 'Leaderboard' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of players to show:' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>


        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
            <label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display only registered players' ); ?></label></p>
        <?php
    }

}