<?php

	include('inc/pdo.inc.php');
	include('inc/fnc.inc.php');

	$Rs = array();
	$rs = $DBS->prepare("SELECT * FROM V_LISTA_CATEGORIA_PRD_USC WHERE COD_EMPRESA = '10'");
	$rs->execute();

	$z = 0;
	while ($r = $rs->fetch(PDO::FETCH_ASSOC)) {
		//print_r($r); exit;
		$Zs[$z] = $r;
		$z++;

		$Rs[$r['COD_CATEGORIA']] = array(
			'id'				=> trim($r['COD_CATEGORIA']),
			'nombre'			=> trim($r['NOM_CATEGORIA']),
			'estado'			=> str_replace('VIGENTE','1',trim($r['ESTADO'])),
		);

		$vs = $DB->prepare('SELECT `id` FROM `area` WHERE `id` = "'.$Rs[$r['COD_CATEGORIA']]['id'].'"');
		$vs->execute();
		if ($vs->rowCount()) {
			$sql = 'UPDATE `area` SET
					`nombre` = "'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_CATEGORIA']]['nombre']).'",
					`slug` = "'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_CATEGORIA']]['nombre'])).'",
					`activo` = "'.$Rs[$r['COD_CATEGORIA']]['estado'].'",
					`modified` = "'.date('Y-m-d H:i:s').'"

				WHERE `id` = "'.$Rs[$r['COD_CATEGORIA']]['id'].'"
			';
		} else {
			$sql = 'INSERT INTO `area` (
					`id`,
					`nombre`,
					`slug`,
					`activo`
				) VALUES (
					"'.$Rs[$r['COD_CATEGORIA']]['id'].'",
					"'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_CATEGORIA']]['nombre']).'",
					"'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_CATEGORIA']]['nombre'])).'",
					"'.$Rs[$r['COD_CATEGORIA']]['estado'].'"
				)
			';
		}

		$upd = $DB->prepare($sql);
		$upd->execute();
		if (!$upd->rowCount()) {
			file_put_contents('/home/ultraschall/public_html/api/area.txt',$sql."\n\r", FILE_APPEND | LOCK_EX);
			//$Rs[$r['id_producto']]['SQL'] = $sql;
		}
	}

	header('Content-Type: application/json');
	echo json_encode($Zs,JSON_PRETTY_PRINT);

?>