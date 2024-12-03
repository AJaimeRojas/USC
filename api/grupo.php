<?php

	include('inc/pdo.inc.php');
	include('inc/fnc.inc.php');

	$Rs = array();
	$rs = $DBS->prepare("SELECT * FROM V_LISTA_SUB_CATEGORIA_PRD_USC WHERE COD_EMPRESA = '10'");
	$rs->execute();

	$z = 0;
	while ($r = $rs->fetch(PDO::FETCH_ASSOC)) {
		//print_r($r); exit;
		$Zs[$z] = $r;
		$z++;

		$Rs[$r['COD_SUB_CATEGORIA']] = array(
			'id'				=> trim($r['COD_SUB_CATEGORIA']),
			'nombre'			=> trim($r['NOM_SUB_CATEGORIA']),
			'estado'			=> str_replace('VIGENTE','1',trim($r['ESTADO'])),
		);

		//print_r($Rs); exit;

		$vs = $DB->prepare('SELECT `id` FROM `grupo_producto` WHERE `id` = "'.$r['COD_SUB_CATEGORIA'].'"');
		$vs->execute();
		if ($vs->rowCount()) {
			$sql = 'UPDATE `grupo_producto` SET
					`nombre` = "'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_SUB_CATEGORIA']]['nombre']).'",
					`activo` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['estado'].'",
					`modified` = "'.date('Y-m-d H:i:s').'",
					`slug`	=	"'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_SUB_CATEGORIA']]['nombre'])).'"

				WHERE `id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['id'].'"
			';
		} else {
			$sql = 'INSERT INTO `grupo_producto` (
					`id`,
					`nombre`,
					`activo`,
					`slug`
				) VALUES (
					"'.$Rs[$r['COD_SUB_CATEGORIA']]['id'].'",
					"'.str_replace($chr_utf,$chr_esp,$Rs[$r['COD_SUB_CATEGORIA']]['nombre']).'",
					"'.$Rs[$r['COD_SUB_CATEGORIA']]['estado'].'",
					"'.str_replace($chr_utf,$chr_min,strtolower($Rs[$r['COD_SUB_CATEGORIA']]['nombre'])).'"
				)
			';
		}

		$upd = $DB->prepare($sql);
		$upd->execute();
		if (!$upd->rowCount()) {
			file_put_contents('/home/ultraschall/public_html/api/grupo.txt',$sql."\n\r", FILE_APPEND | LOCK_EX);
			//$Rs[$r['id_producto']]['SQL'] = $sql;
		}
	}

	header('Content-Type: application/json');
	echo json_encode($Zs,JSON_PRETTY_PRINT);

?>