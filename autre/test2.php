<?php
//réception de la liste
$input_cb ="";

if (isset($_POST['disponibles'])) {
	$array = unserialize($_POST['disponibles']);
	$input_cb = 0;
}
else if (isset($_POST['non_autorisés'])) {
	$array = unserialize($_POST['non_autorisés']);
	$input_cb = 1;
}

convert($array, $input_cb);

function convert ($data, $input_cb, $filename='export',$delimiter = ';',$enclosure = '"') {

	header("Content-disposition: attachment; filename=$filename.csv");
	header("Content-Type: text/csv");

    $fp = fopen("php://output", 'w');

    // Insert the UTF-8 BOM in the file
    fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

    // Création des noms de colonnes
	$headers = ["Code", "Libellé"];
    fputcsv($fp,$headers,$delimiter,$enclosure);

    // Ajouter les données au fichier
		if($input_cb === 1) { //Si non-autorisés
		foreach ($data as $fields) {
		if ($fields['input_cb'] === 1) {

		$sorted = array_pop($fields);	// Suppression de la colonne input_cb
        fputcsv($fp, $fields,$delimiter,$enclosure);
    }
		}} else { // Si disponibles
		foreach ($data as $fields) {
		$sorted = array_pop($fields);	// Suppression de la colonne input_cb
		fputcsv($fp, $fields,$delimiter,$enclosure);
		} }

    // fermer le fichier
    fclose($fp);

    // Stop the script
    die();
}

?>
