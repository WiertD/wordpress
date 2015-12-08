<?php
/**
 * GG KP Excel
 * 
 * @package   GG KP Excel Admin
 * @author    GemeneGronden
 * @license   GPL-2.0+
 * @link      http://gemenegronden.nl
 */

/**
 * @package GGKP_Excel_Admin
 * @author  GemeneGronden
 */
class GGKP_Excel_Admin {

    protected static $instance = null;
    protected $sheets = NULL;
    protected $plugin_screen_hook_suffix = null;

    private function __construct() {

        /*
         * @TODO :
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        if( ! is_super_admin() ) {
            return;
        }

        $plugin = GGKP_Excel::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Add the options page and menu item.
        add_action( 'admin_menu', array( $this, 'register_my_custom_menu_page' )  );

        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
        
        $this->upload_dir         = wp_upload_dir();    
        $this->upload_base_url    = $this->upload_dir['baseurl'].'/' . GGKP_MAP;
        $this->upload_dir         = $this->upload_dir['basedir'].'/' . GGKP_MAP;
        $this->admin_plugin_url   = admin_url( "options-general.php?page=".$_GET["page"] );
    }

    function register_my_custom_menu_page(){
        $this->plugin_screen_hook_suffix = add_menu_page(
                __( 'Klompenpad Werkblad', $this->plugin_slug ),
                __( 'Klompenpad Werkblad', $this->plugin_slug ),
                'edit_pages',
                $this->plugin_slug.'-admin-menu',
                array( $this, 'display_plugin_admin_menu_page' ),
                '',
                '6.12'
                );
    }

    function create_json_file($file_name){

        if(empty($file_name)){
            return false;
        }
        if(empty($_FILES['file']) || empty($_FILES["file"]['name'])){
            return false;
        }

        require_once( plugin_dir_path( __FILE__ ) . '../includes/simplexlsx.class.php' );
        $sw = get_option('ggkp_software','1');
        $xlsx = new SimpleXLSX( $_FILES['file']['tmp_name'] );
        $sheetNames = $xlsx->sheetNames();
        if(is_array($sheetNames)){
            $sheets = array();
            foreach($sheetNames as $sheetId => $sheetName){
                if($sheetId == $sw) {
                    $filteredData = $this->filterData($xlsx, $sw);
                    $filteredJsonData = json_encode($filteredData);
                    $uploadResult = $this->createJsonFilteredDataFile( $filteredJsonData, $file_name );
                }
                $sheets[] = $sheetId . ' -> ' . $sheetName;
            }
            $this->sheets = '<p style="float: left; width: 100%">Werblad nummers ';
            $this->sheets .= '<br />' . $uploadResult . '</p>';
            $this->sheets .= '<br />' . implode('<br />', $sheets) . '</p>';
            $this->sheets .= '<p>Wanneer het Klompenpad werkblad wordt geopend en vervolgens wordt opgeslagen met LibreOffice kloppen de werkbladnummers niet meer. Alle werkbladnummers worden dan met 1 opgehoogd.</p><p>Wanneer het bestand met MSExcel wordt opgeslagen zijn de werkbladnummers oplopend vanaf 1.</p><p>Dit kan worden gecorrigeerd op de <a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">settings pagina</a> van deze plugin, dus de waarde 2 geven als het bestand met LibreOffice is opgeslagen, anders 1</p>';

        }
        return $uploadResult;
    }

    /**
     * @param $jsonData
     * @param $new_file_name
     *
     * @return array
     */

    public function createJsonFilteredDataFile($jsonData, $new_file_name ) {

        $this->upload_dir = wp_upload_dir();
        $this->upload_dir = $this->upload_dir['basedir'] . '/' . GGKP_MAP;
        $file_name        = $this->upload_dir . '/' . $new_file_name . '.json';
        $fp = fopen( $file_name, "wb" );
        if( fwrite( $fp, $jsonData )) {
          fclose( $fp );
          return true;
        }
        return false;
    }
    private function columnIndex( $cell = 'A1' ) {

        if (preg_match("/([A-Z]+)(\d+)/", $cell, $matches)) {

            $col = $matches[1];
            $row = $matches[2];

            $colLen = strlen($col);
            $index = 0;

            for ($i = $colLen-1; $i >= 0; $i--)
                $index += (ord($col{$i}) - 64) * pow(26, $colLen-$i-1);

            return array($index-1, $row-1);
        } else
            throw new Exception("Invalid cell index.");
    }

    public function filterData($xlsx, $sheetNumber=1) {
        $data = $xlsx->rows($sheetNumber);

        $rowIndex['pageid'] = 2;
        $rowIndex['data'] = 3;

        $tmp = $this->columnIndex('A1');
        $folderweb = $tmp[0];
        $tmp = $this->columnIndex('B1');
        $koffieweb = $tmp[0];
        $tmp = $this->columnIndex('Y1');
        $bedrijf = $tmp[0];
        $tmp = $this->columnIndex('AD1');
        $straat = $tmp[0];
        $tmp = $this->columnIndex('AE1');
        $postcode = $tmp[0];
        $tmp = $this->columnIndex('AF1');
        $woonplaats = $tmp[0];
        $tmp = $this->columnIndex('AJ1');
        $website = $tmp[0];
        $tmp = $this->columnIndex('AK1');
        $open = $tmp[0];

        $tmp = $this->columnIndex('AL1');
        $colIndex['folders']['start'] = $tmp[0];
        $tmp = $this->columnIndex('FA1');
        $colIndex['folders']['eind'] = $tmp[0];

        $tmp = $this->columnIndex('FB1');
        $colIndex['relaties']['start'] = $tmp[0];
        $tmp = $this->columnIndex('JQ1');
        $colIndex['relaties']['eind'] = $tmp[0];

        $j = $rowIndex['data'];
        $pid = $rowIndex['pageid'];

        $output = array();
        while( !empty($data[$j][$bedrijf])) {
            /**
             * Verkooppunten brochure
             */
            if($data[$j][$folderweb] == 1 ) {
                $i = $colIndex['folders']['start'];
                while( $i <= $colIndex['folders']['eind']) {
                    if($data[$j][$i] >= 1) {
                        $regel = array();
                        $pageid = $data[$pid][$i];
                        if( !empty($data[$j][$bedrijf] )) {
                            if( !empty($data[$j][$website] )) {
                                $regel[] = '<a href="' . $data[$j][$website] . '">' . $data[$j][$bedrijf] . '</a>';
                            } else {
                                $regel[] = $data[$j][$bedrijf];
                            }
                        }
                        if( !empty($data[$j][$straat] )) {
                            $regel[] = $data[$j][$straat];
                        }
                        if( !empty($data[$j][$woonplaats] )) {
                            $regel[] = $data[$j][$woonplaats];
                        }
                        $output[$pageid]['folders'][$j] = implode(', ',$regel);
                    }
                    $i++;
                }
            }
            /**
             * Horeca onderweg
             */
            if($data[$j][$koffieweb] == 1 ) {
                $i = $colIndex['relaties']['start'];
                while( $i <= $colIndex['relaties']['eind']) {
                    if($data[$j][$i] == 1) {
                        $regel = array();
                        $pageid = $data[$pid][$i];
                        if( !empty($data[$j][$bedrijf] )) {
                            if( !empty($data[$j][$website] )) {
                                $regel[] = '<a href="' . $data[$j][$bedrijf] . '">' . $data[$j][$bedrijf] . '</a>';
                            } else {
                                $regel[] = $data[$j][$bedrijf];
                            }
                        }
                        if( !empty($data[$j][$straat] )) {
                            $regel[] = $data[$j][$straat];
                        }
                        if( !empty($data[$j][$woonplaats] )) {
                            $regel[] = $data[$j][$woonplaats];
                        }
                        if( !empty($data[$j][$open] )) {
                            $regel[] = $data[$j][$open];
                        }
                        $output[$pageid]['relaties'][$j] = implode(', ',$regel);
                    }
                    $i++;
                }
            }
            $j++;
        }
        return $output;
    }

    function getFileList(){

        $this->upload_dir         = wp_upload_dir();    
        $this->upload_dir         = $this->upload_dir['basedir'].'/' . GGKP_MAP;

        //get all image files with a .jpg extension.
        $files = glob($this->upload_dir . "/*.options.json");

        for($i=0;$i<count($files);$i++){ 
            $file_data[$i] = (array) json_decode(file_get_contents($files[$i]));
        }

        return $file_data;  
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        if( ! is_super_admin() ) {
            return;
        } 

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'GGKP Excel Settings', $this->plugin_slug ),
			__( 'GGKP Excel', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}
    
    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_menu_page() {

        $this->upload_dir         = wp_upload_dir();    
        $this->upload_base_url    = $this->upload_dir['baseurl'].'/' . GGKP_MAP;
        $this->upload_dir         = $this->upload_dir['basedir'].'/' . GGKP_MAP;
        $this->admin_plugin_url   = admin_url( "options-general.php?page=".$_GET["page"] );

        if(!is_dir($this->upload_dir)){
            $createUploadFolderStarted  = true;
            $createUploadFolderRes      = mkdir($this->upload_dir);
        }
        if(!is_dir($this->upload_dir)){
            $uploadFolderDoesNotExists = true;
        }

        if (isset($_FILES['file'])) {
            $uploadStarted  = true;
            $uploadResult   = $this->create_json_file(GGKP_WERKBLAD); 
        }

        $sheets = $this->sheets;
        $file_data = $this->getFileList();
        include_once( 'views/admin-menu.php' );
    }

}
