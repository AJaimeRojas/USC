<?php

	include('inc/pdo.inc.php');
	include('inc/fnc.inc.php');

	$Rs = array();
	$rs = $DBS->prepare("SELECT * FROM V_LISTA_PAISES_USC");
	$rs->execute();

	$z = 0;
	while ($r = $rs->fetch(PDO::FETCH_ASSOC)) {
		//print_r($r); exit;
		$Zs[$z] = $r;
		$z++;

		$Rs[$r['COD_PAIS']] = array(
			'id'				=> trim($r['COD_PAIS']),
			'nombre'			=> trim($r['NOM_PAIS']),
			'estado'			=> str_replace('VIGENTE','1',trim($r['ESTADO'])),
		);

		//print_r($Rs); exit;

		$vs = $DB->prepare('SELECT `id` FROM `procedencia` WHERE `id` = "'.$Rs[$r['COD_PAIS']]['id'].'"');
		$vs->execute();
		if ($vs->rowCount()) {
			$sql = 'UPDATE `procedencia` SET
					`nombre` = "'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_PAIS']]['nombre']).'",
					`slug` = "'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_PAIS']]['nombre'])).'",
					`activo` = "'.$Rs[$r['COD_PAIS']]['estado'].'",
					`modified` = "'.date('Y-m-d H:i:s').'"

				WHERE `id` = "'.$Rs[$r['COD_PAIS']]['id'].'"
			';
		} else {
			$sql = 'INSERT INTO `procedencia` (
					`id`,
					`nombre`,
					`slug`,
					`estado`
				) VALUES (
					"'.$Rs[$r['COD_PAIS']]['id'].'",
					"'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_PAIS']]['nombre']).'",
					"'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_PAIS']]['nombre'])).'",
					"'.$Rs[$r['COD_PAIS']]['estado'].'"
				)
			';
		}
		//$Rs[$r['COD_PAIS']]['sql'] = $sql;
		$upd = $DB->prepare($sql);
		$upd->execute();
	}

	header('Content-Type: application/json');
	echo json_encode($Zs,JSON_PRETTY_PRINT);

?>