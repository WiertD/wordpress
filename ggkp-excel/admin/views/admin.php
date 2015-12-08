<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   GGKP_Excel
 * @author    Wiert Dijkkamp <info@gemenegronden.nl>
 * @license   GPL-2.0+
 * @link      http://gemenegronden.nl
 * @copyright 2015 Wiert Dijkkamp
 */
?>


<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <br />
    <?php
      if ( $_SERVER["REQUEST_METHOD"] == "POST" ){
        update_option('ggkp_software', $_POST['software']);
        update_option('ggkp_folder', $_POST['folder']);
        update_option('ggkp_relatie', $_POST['relatie']);
    }

    echo '<form method="POST">';
    echo '<hr />';
    echo '<table>';

    echo '<tr><td>De gebruikte software (1 - MS-Excel, 2 - Libre Office):</td>';
    echo '<td><input type="text" name="software" value="' . get_option('ggkp_software','1') . '"></td></tr>';
    echo '<tr><td>Kop verkooppunten folders:</td>';
    echo '<td><input type="text" name="folder" value="' . get_option('ggkp_folder','Verkooppunten brochure:') . '"></td></tr>';
    echo '<tr><td>Kop relaties (horeca onderweg):</td>';
    echo '<td><input type="text" name="relatie" value="' . get_option('ggkp_relatie','Horeca onderweg:') . '"></td></tr>';
    echo '</table>';
    echo '<hr />';
    echo '<input type="submit" value="Save">';
    echo '</form>';
?>
</div>
            
       


