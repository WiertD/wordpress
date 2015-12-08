<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   GGKP_Excel
 * @author    GemeneGronden <info@gemenegronden.nl>
 * @license   GPL-2.0+
 * @link      http://gemenegronden.nl
 */
?>

<?php if (isset($createUploadFolderStarted)) { ?>
    
    <?php if($createUploadFolderRes){ ?>
        <div id="message" class="updated fade"><p>Upload folder successvol aangemaakt. </p></div>
    <?php }else{ ?>
        
    <?php }?>

<?php } ?>


<?php if (isset($uploadFolderDoesNotExists)) { ?>
    
<div id="message" class="error fade"><p>De upload folder kan miet worden gemaakt. Controleer de rechten. </p></div>

<?php } ?>


<?php if(isset($deleteStarted)){ ?>
    
    <?php if($deleteResult===TRUE){ ?>
        <div id="message" class="updated fade"><p>Bestand is verwijderd! </p></div>
    <?php }else{ ?>
        <div id="message" class="error fade"><p>Het is niet gelukt het bestand te verwijderen. </p></div>
    <?php  }?>

<?php  }?>



<?php if (isset($uploadStarted)) { ?>
    
    <?php if(!$uploadResult){ ?>
        <div id="message" class="error fade"><p>Upload mislukt! Controleer de instelling voor de gebruikte software!/p></div>
    <?php }else{ ?>
        <div id="message" class="updated fade"><p>Het bestand is succesvol bijgewerkt </p></div>
    <?php }?>

<?php } ?>

<div class="wrap">

	<?php screen_icon('edit-pages'); ?>
	<h2>
        <?php echo esc_html( get_admin_page_title() ); ?>
    </h2>

    <div style="width: 100%; float: left;">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder"  style="width:520px">
                <div id="post-body-content">
                    <div id="namediv" class="stuffbox">
                        <h3>Klompenpad werkblad bijwerken</h3>
                        <div class="inside">
                            <form method="post" enctype="multipart/form-data">
                                <table class="form-table" style="width:500px">
                                    <tr>
                                        <td>Bestand:</td>
                                        <td><input type="file" name="file"  style="width:400px"   />Alleen *.xlsx bestanden</td>
                                    </tr>    
                                    <tr>
                                        <td></td>
                                        <td><input type="submit" class="button-primary" value="Start upload" /></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div><!-- /post-body-content -->
            </div>
        </div>
    </div>  

   
</div>
 
<?php echo $sheets;?>



