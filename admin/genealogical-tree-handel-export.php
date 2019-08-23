<?php
if(isset($_POST['submit'])){
	check_admin_referer( 'export_ged' );

	if(!current_user_can('administrator')){
		echo esc_html( __( 'You are not authorized to do this.', 'genealogical-tree' ) );
		die();
	}

	$family = sanitize_text_field($_POST['family']);

	$text = '';
	$text .='0 HEAD'."\n";
	$text .='1 SOUR Genealogical Tree'."\n";
	$text .='2 NAME Genealogical Tree – Wp Family Tree Plugin'."\n";
	$text .='2 VERS 1.0.0'."\n";
	$text .='2 CORP ak devs'."\n";
	$text .='3 ADDR 75  rue des Coudriers'."\n";
	$text .='4 CONT MULHOUSE'."\n";
	$text .='4 CONT FR'."\n";
	$text .='1 DATE '.date('d M Y')."\n";
	$text .='2 TIME '.date('H:i:s')."\n";
	$text .='1 FILE '.$family.'.ged'."\n";
	$text .='1 SUBM @SUBM@'."\n";
	$text .='1 GEDC'."\n";
	$text .='2 VERS 5.5'."\n";
	$text .='2 FORM LINEAGE-LINKED'."\n";
	$text .='1 CHAR UTF-8'."\n";
	$text .='0 @SUBM@ SUBM'."\n";
	$text .='0 TRLR'."\n";

	$gt_files_dir = wp_get_upload_dir()['basedir'].'/gt-files/';

	if (!file_exists($gt_files_dir)) {
		print_r($gt_files_dir);
		mkdir($gt_files_dir, 0777, true);
	}

	$myfile = fopen($gt_files_dir.$family.'.ged', 'w') or die('Unable to open file!');
	fwrite($myfile, $text);
	fclose($myfile);
	$zip = new ZipArchive();
	if ($zip->open($gt_files_dir.$family.'.zip', ZipArchive::CREATE) != TRUE) {
		 die ("Could not open archive");
	}
	$zip->addFile($gt_files_dir.$family.'.ged', $family.'.ged');
	$zip->close();

	echo '<script> location.replace("'.wp_get_upload_dir()['baseurl'].'/gt-files/'.$family.'.zip") </script>';

}
