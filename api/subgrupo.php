<?php

	include('inc/pdo.inc.php');
	include('inc/fnc.inc.php');

	$Rs = array();
	$rs = $DBS->prepare("SELECT * FROM V_LISTA_CATEGORIAS_SUB_CATEGORIA_USC WHERE COD_EMPRESA = '10'");
	$rs->execute();

	$ds = $DB->prepare('TRUNCATE TABLE `area_grupo`');
	$ds->execute();

	$z = 0;
	while ($r = $rs->fetch(PDO::FETCH_ASSOC)) {
		//print_r($r); exit;
		$Zs[$z] = $r;
		$z++;

		$Rs[$r['COD_SUB_CATEGORIA']] = array(
			'categoria_id'		=> trim($r['COD_CATEGORIA']),
			'categoria'		=> str_replace($chr_utf,$chr_iso,trim($r['NOM_CATEGORIA'])),
			'subcategoria_id'	=> trim($r['COD_SUB_CATEGORIA']),
			'subcategoria'		=> str_replace($chr_utf,$chr_iso,trim($r['NOM_SUB_CATEGORIA'])),
			'codigo'			=> '',
		);

		//print_r($Rs); exit;

		//		    echo 'SELECT * FROM `area_grupo` WHERE `area_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['categoria_id'].'" AND `grupo_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['subcategoria_id'].'"'; exit;

		$vs = $DB->prepare('SELECT * FROM `area_grupo` WHERE `area_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['categoria_id'].'" AND `grupo_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['subcategoria_id'].'"');
		$vs->execute();
		if ($vs->rowCount()) {
			$sql = 'UPDATE `area_grupo` SET
					`area_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['categoria_id'].'",
					`modified` = "'.date('Y-m-d H:i:s').'",
					`todelete` = 0

				WHERE `grupo_id` = "'.$Rs[$r['COD_SUB_CATEGORIA']]['subcategoria_id'].'"
			';
		} else {
			$sql = 'INSERT INTO `area_grupo` (
					`area_id`,
					`grupo_id`,
					`codigo`
				) VALUES (
					"'.$Rs[$r['COD_SUB_CATEGORIA']]['categoria_id'].'",
					"'.$Rs[$r['COD_SUB_CATEGORIA']]['subcategoria_id'].'",
					"'.$Rs[$r['COD_SUB_CATEGORIA']]['codigo'].'"
				)
			';
		}

		$upd = $DB->prepare($sql);
		$upd->execute();
		if (!$upd->rowCount()) {
			file_put_contents('/home/ultraschall/public_html/api/subgrupo.txt',$sql."\n\r", FILE_APPEND | LOCK_EX);
			//$Rs[$r['id_producto']]['SQL'] = $sql;
		}
	}

	header('Content-Type: application/json');
	echo json_encode($Rs,JSON_PRETTY_PRINT);

?>