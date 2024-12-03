<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 35060);
	include('inc/pdo.inc.php');
	include('inc/fnc.inc.php');


	$Rs = array();
	//$rs = $DBS->prepare("SELECT TOP 1 * FROM UVW_PRODUCTOS_PORTAL_USC");
	$rs = $DBS->prepare("SELECT * FROM UVW_PRODUCTOS_PORTAL_USC");
	$rs->execute();

	$z = 0;
	while ($r = $rs->fetch(PDO::FETCH_ASSOC)) {
		//print_r($r); exit;
		$Zs[$z] = $r;
		$z++;
		
		if($r['Registro']==0){
		    $descripcionReg = "No";
		}else{
		    $descripcionReg = " Si, Nro Reg.: ".trim($r['numdoc']);
		}


		$Rs[$r['id_producto']] = array(
			'id'				=> trim($r['id_producto']),
			'codigo'			=> trim($r['prd_codprd']),
			'nombre'			=> trim($r['prd_desesp']),
			'aux_codaux'		=> trim($r['aux_codaux']),
			'AUX_NOMAUX'		=> trim($r['AUX_NOMAUX']),
			'marca_id'		=> trim($r['mpr_codmpr']),
			'marca'			=> trim($r['MPR_DESCOR']),
			'area_id'			=> trim($r['lpd_codlpd']),
			'area'			=> trim($r['lpd_nomcor']),
			'grupo_id'		=> trim($r['GRP_CODGRP']),
			'procedencia_id'	=> trim($r['pai_codpai']),
			'procedencia'		=> trim($r['PAI_NOMCOR']),
			'modelo'			=> trim($r['Modelo']),
			'precio_PEN'		=> trim($r['PrecioCop']),
			'precio_USD'		=> trim($r['PrecioDol']),
			'um'				=> trim($r['UMed']),
			'todelete'		=> trim($r['flagdelete']),
			'registro_sanitario'	=> trim($r['Registro']),
			'tiene_imagen'		=> trim($r['TieneIMG']),
			'tiene_catalogo'	=> trim($r['TieneCAT']),
			'slug'			=> trim($r['slug']),
			'descripcion'			=> $descripcionReg,
		);

		if ($r['TieneIMG']=='SI') {
			file_put_contents('/home/ultraschall/public_html/ultraschall/web/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG',$r['Imagen']);
			//$urlImg = "https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG";
			//$urlGaleria="a:1:{i:0;s:71:\"https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG\";}";
		}else{
		    //$urlImg = "https://www.ultraschall.co/uploads/productos/clase-b-iii.jpg";
			//$urlGaleria="a:1:{i:0;s:71:\"https://www.ultraschall.co/uploads/productos/clase-b-iii.jpg\";}";
		}

		if ($r['TieneCAT']=='SI') {
			file_put_contents('/home/ultraschall/public_html/ultraschall/web/images/productos/catalogos/'.$r['prd_codprd'].'CAT_ORIG.PDF',$r['Catalogo']);
		}

        //
        $conexion=new mysqli("localhost", "ultraschall_staff", "$6x7yE0JKG]t6@DQ", "ultraschall_staff");
        $conexion->query("SET NAMES 'utf8'");
        $cquery = 'SELECT count(`id`) as total FROM `producto` WHERE `id` = "'.trim($r['id_producto']).'"';
        $consulta=mysqli_query($conexion,$cquery);		
		while($filas=$consulta->fetch_assoc()){
		    $total = $filas["total"];
        }		
        
        
		//$vs = $DB->prepare('SELECT count(`id`) as total FROM `producto` WHERE `id` = "'.trim($r['id_producto']).'"');
		//$vs->execute();
		if ($total>0) {
			$sql = 'UPDATE `producto` SET
					`area_id` = "'.trim($r['lpd_codlpd']).'",
					`grupo_id` = "'.trim($r['GRP_CODGRP']).'",
					`marca_id` = "'.trim($r['mpr_codmpr']).'",
					`procedencia_id` = "'.trim($r['pai_codpai']).'",
					`precio` = "'.trim($r['PrecioDol']).'",
					`preciocop` = "'.trim($r['PrecioCop']).'",
					`nombre` = "'.str_replace($chr_utf,$chr_esp,$Rs[$r['id_producto']]['nombre']).'",
					`slug` = "'.trim($r['slug']).'",
                    `descripcion` = "'.trim($descripcionReg).'",
					`modified` = "'.date('Y-m-d H:i:s').'",
					`imagenvalid` = "https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG" ,
					`galeriavalid` = "a:1:{i:0;s:71:\"https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG\";}"

				WHERE `id` = "'.trim($r['id_producto']).'"';
		} else {
			$sql = 'INSERT INTO `producto` (
					`id`,
					`area_id`,
					`grupo_id`,
					`marca_id`,
					`procedencia_id`,
					`imagen`,
					`galeria`,
					`informacion`,
					`catalogo`,
					`created`,
					`modified`,
					`created_user`,
					`modified_user`,
					`orden`,
					`slug`,
					`descripcion`,
					`destacado`,
					`registrosanitario`,
					`activo`,
					`codigo`,
					`nombre`,
					`modelo`,
					`precio`,
					`preciocop`,
					`um`,
					`imagenvalid`,
					`galeriavalid`,
					`catalogovalid`,
					`todelete`
				) VALUES (
					"'.$r['id_producto'].'",
					"'.$Rs[$r['id_producto']]['area_id'].'",
					"'.$Rs[$r['id_producto']]['grupo_id'].'",
					"'.$Rs[$r['id_producto']]['marca_id'].'",
					"'.$Rs[$r['id_producto']]['procedencia_id'].'",
					NULL,
					"a:0:{}",
					"a:0:{}",
					NULL,
					"'.date('Y-m-d H:i:s').'",
					"'.date('Y-m-d H:i:s').'",
					"0",
					"0",
					"1",
					"'.trim($r['slug']).'",
				    "'.trim($descripcionReg).'",
					"0",
					"'.$Rs[$r['id_producto']]['registro_sanitario'].'",
					"1",
					"'.$Rs[$r['id_producto']]['codigo'].'",
					"'.str_replace($chr_utf,$chr_esp,$Rs[$r['id_producto']]['nombre']).'",
					"'.$Rs[$r['id_producto']]['modelo'].'",
					"'.$Rs[$r['id_producto']]['precio_USD'].'",
					"'.$Rs[$r['id_producto']]['precio_PEN'].'",
					"UND",
					"https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG",
					"a:1:{i:0;s:71:\"https://www.ultraschall.co/images/productos/imagenes/'.$r['prd_codprd'].'_IMGPRD.JPG\";}",
					NULL,
					"'.$Rs[$r['id_producto']]['todelete'].'"
				)
			';
		}
		//$upd = $DB->prepare($sql);
		//$upd->execute();
		$upd=mysqli_query($conexion,$sql);
		/*
		if (!$upd->rowCount()) {
			file_put_contents('/home/ultraschall/public_html/api/productos.txt',$sql."\n\r", FILE_APPEND | LOCK_EX);
			//$Rs[$r['id_producto']]['SQL'] = $sql;
		}*/
	}


	//header('Content-Type: application/json');
	//echo json_encode($Zs,JSON_PRETTY_PRINT);

?>