<?php
/**
 * GG KP Excel
 *
 * @package   GG KP Excel Public
 * @author    GemeneGronden
 * @license   GPL-2.0+
 * @link      http://gemenegronden.nl
 */


/**
 * Plugin class. This class should be used to work with the
 * public-facing side of the WordPress site.
 */
class GGKP_Excel {

    protected static $instance = null;
    /**
     * @TODO - Rename "plugin-name" to the name your your plugin
     *
     * Unique identifier for your plugin.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * plugin file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_slug = 'ggkp-excel';

    public function __construct() {
        $upload_dir                = wp_upload_dir();    
        $this->upload_base_url     = $upload_dir['baseurl'].'/' . GGKP_MAP;
        $this->upload_dir          = $upload_dir['basedir'].'/' . GGKP_MAP;
        $this->admin_plugin_url    = admin_url( "options-general.php?page=".$_GET["page"] );
        $this->plugin_dir          = plugin_dir_path( __FILE__ );
        $this->template_dir        = $this->plugin_dir.'/templates';

        add_filter( 'the_content', array( $this, 'append_info' ) );
    }

    public static function get_instance() {

        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Return the plugin slug.
     *
     * @since    1.0.0
     *
     *@return    Plugin slug variable.
     */
    public function get_plugin_slug() {
        return $this->plugin_slug;
    }
    
    public function append_info( $content ) {

        $data = $this->get_filtered_data();
        if($data) {
            $id = get_the_ID();
            $folders = '';
            $relaties = '';
            if(is_array($data[$id])) {
                $row = $data[$id];
                $data = null;
                if(array_key_exists('folders',$row)) {
                    $folders = '<p class="ggkp-header">'. get_option('ggkp_folder','Verkooppunten brochure:') . '</p><ul class="ggkp"><li>';
                    $folders .= implode('</li><li>', $row['folders']) . '</li></ul>';
                }
                if(array_key_exists('relaties',$row)) {
                    $relaties = '<p class="ggkp-header">' . get_option('ggkp_relatie','Horeca onderweg:') . '</p><ul class="ggkp"><li>';
                    $relaties .= implode('</li><li>', $row['relaties']) . '</li></ul>';
                }
            }
            return $content . $folders . $relaties;
        }
        return $content;
    }

    public function get_filtered_data(){
        $sw = get_option('ggkp_software','1');
        $file_name = GGKP_WERKBLAD . '.json';
        $file_path_name = $this->upload_dir.'/'.$file_name;
        if(file_exists($file_path_name)) {
            return json_decode(file_get_contents($file_path_name), true);
        }
        return false;
    }
    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

            if ( $network_wide  ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_activate();
                }

                restore_current_blog();

            } else {
                self::single_activate();
            }

        } else {
            self::single_activate();
        }

    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

            if ( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_deactivate();

                }

                restore_current_blog();

            } else {
                self::single_deactivate();
            }

        } else {
            self::single_deactivate();
        }

    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        // @TODO: Define activation functionality here
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        // @TODO: Define deactivation functionality here
    }

}
