<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
unset($_SESSION['prestationINAMIfiltre']);
require_once("include/fct.php");
valideuser();

$titre = '<h1>Création de filtres pour les infos INAMI</h1>';

if(isset($_POST['action'])){
	$query = isset($_POST['string'])?$_POST['string']:'';
	$action = $_POST['action'];
	if($action === 'search'){
		$select = 0;
		if(!empty($query)){
			$strArray = explode("&", $query);
			foreach($strArray as $item) {
				$array = explode("=", $item);
				$returndata[$array[0]] = $array[1];
			}
			$termOk = '';
			$termOksize = 0;
			foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
				if(!empty($returndata['searchcode']) && !empty($returndata['searchlib'])){
					if(strpos($inami['code'],$returndata['searchcode']) !== FALSE && strpos(strtolower($inami['libelle']),strtolower($returndata['searchlib'])) !== FALSE){
						$termOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
						$_SESSION['prestationINAMIfiltre'][$id]['code'] = $inami['code'];
						$_SESSION['prestationINAMIfiltre'][$id]['libelle'] = $inami['libelle'];
						$_SESSION['prestationINAMIfiltre'][$id]['input_cb'] = $inami['input_cb'];
						$termOksize++;
					}
				
				}else{
					if(!empty($returndata['searchcode'])){
						$reloadlist = TRUE;
						if($reloadlist){
							if(intval($inami['code']) >= intval($returndata['searchcode'])){
								if(empty($select)){
									$select = $id;
								}
								$termOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
								$_SESSION['prestationINAMIfiltre'][$id]['code'] = $inami['code'];
								$_SESSION['prestationINAMIfiltre'][$id]['libelle'] = $inami['libelle'];
								$_SESSION['prestationINAMIfiltre'][$id]['input_cb'] = $inami['input_cb'];
								$termOksize++;
							}
							//$termOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
							//$termOksize++;
						}else{
							if(intval($inami['code']) >= intval($returndata['searchcode'])){
								$select = $id;
								break;
							}
						}
					} 
					
					if(!empty($returndata['searchlib'])){
						if(strpos(strtolower($inami['libelle']),strtolower($returndata['searchlib'])) !== FALSE){
							if(empty($select)){
								$select = $id;
							}
							$termOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
							$_SESSION['prestationINAMIfiltre'][$id]['code'] = $inami['code'];
							$_SESSION['prestationINAMIfiltre'][$id]['libelle'] = $inami['libelle'];
							$_SESSION['prestationINAMIfiltre'][$id]['input_cb'] = $inami['input_cb'];
							$termOksize++;
						}
					}
				}
			}
		}else{
			$termOk = '';
			$termOksize = 0;
			foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
				if(empty($select)){
					$select = $id;
				}
				$termOk .= "<option value=\"".$id."\">[".$inami['code']."] ".$inami['libelle']."</option>";
				$termOksize++;
			}
		}
		if($termOksize < 26){
			$termOksize = 26;
		}
		$arr = array('querystring' => $query, 'termok' => $termOk, 'termoksize' => $termOksize, 'select' => $select, 'status' => 'success');
		echo json_encode($arr);
	}
	if($action === 'insert'){
		$strArray = explode("&", $query);
		foreach($strArray as $item) {
			$array = explode("=", $item);
			$returndata[$array[0]] = $array[1];
		}
		$termNotOk = '';
		$termNotOksize = 0;
		if(!empty($returndata['id'])){
			insertFiltre($returndata['id'],'prestations');
		}
		if(!empty($returndata['fromcode']) && !empty($returndata['tocode'])){
			foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
				if(intval($inami['code']) >= intval($returndata['fromcode']) && intval($inami['code']) <= intval($returndata['tocode'])){
					if($inami['input_cb'] == 0){
						insertFiltre($id,'prestations');
					}
				}
			}
		}
		foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
			if($inami['input_cb'] == 1){
				$termNotOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
				$termNotOksize++;
			}
		}
		if($termNotOksize < 26){
			$termNotOksize = 26;
		}
		$arr = array('querystring' => $query, 'termnotok' => $termNotOk, 'termnotoksize' => $termNotOksize, 'status' => 'success');
		echo json_encode($arr);
	}
	if($action === 'delete'){
		$strArray = explode("&", $query);
		foreach($strArray as $item) {
			$array = explode("=", $item);
			$returndata[$array[0]] = $array[1];
		}
		$termNotOk = '';
		$termNotOksize = 0;
		if(!empty($returndata['id'])){
			deleteFiltre($returndata['id'],'prestations');
		}
		if(!empty($returndata['fromcode']) && !empty($returndata['tocode'])){
			foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
				if(intval($inami['code']) >= intval($returndata['fromcode']) && intval($inami['code']) <= intval($returndata['tocode'])){			
					deleteFiltre($id,'prestations');
				}
			}
		}
		foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
			if($inami['input_cb'] == 1){
				$termNotOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
				$termNotOksize++;
			}
		}
		if($termNotOksize < 26){
			$termNotOksize = 26;
		}
		$arr = array('querystring' => $query, 'termnotok' => $termNotOk, 'termnotoksize' => $termNotOksize, 'status' => 'success');
		echo json_encode($arr);
	}
	exit();
}

function insertFiltre($_id,$_tableName){
	$db = new dbdsjweb();
	$db->connect() or exit("no DB connection !");
	if(strpos($_id,',') !== false){
		$tabId = explode(',',$_id);
	}else{
		$tabId = array($_id);
	}
	foreach($tabId as $key => $value){
		
		if ($_SESSION['prestationsINAMI'][$value]['input_cb'] !== 1) {
			$_SESSION['test'] = $_SESSION['prestationsINAMI'][$value]['libelle'];
		//$sqlExist = 'SELECT * FROM filtresInfosSejours WHERE table_name=\''.$_tableName.'\' AND table_id=\''.$value.'\'';
		//$rsExist = $db->query($sqlExist);
		//if(!is_array($rowExist = $db->next_record($rsExist))){
			$sqlInsert  = 'INSERT INTO filtresInfosSejours (table_id,table_name) VALUES (\''.$value.'\',\''.$_tableName.'\')';
			$_SESSION['prestationsINAMI'][$value]['input_cb'] = 1;
			$db->query($sqlInsert);
			
		//}
		}
	}
}

function deleteFiltre($_id,$_tableName){
	$db = new dbdsjweb();
	$db->connect() or exit("no DB connection !");
	if(strpos($_id,',') !== false){
		$tabId = explode(',',$_id);
	}else{
		$tabId = array($_id);
	}
	foreach($tabId as $key => $value){
		$sqlDelete  = 'DELETE FROM filtresInfosSejours WHERE table_name=\''.$_tableName.'\' AND table_id=\''.$value.'\'';
		$_SESSION['prestationsINAMI'][$value]['input_cb'] = 0;
		$db->query($sqlDelete);
	}
}

//unset($_SESSION['prestationsINAMI']);
if(empty($_SESSION['prestationsINAMI'])){
	$db = new dbdsjweb();
	$db->connect() or exit("no DB connection !");
	$sql = "SELECT p.id as id,
	case
		when (select count(*) from filtresInfosSejours f where table_name = 'prestations' and table_id=p.id) > 0 then 1 else 0 
	end as input_cb,
	p.code as code,p.libelle as libelle,
	case
		when (SELECT count(*) as NB from LC_INAMI where  inami=p.code) > 0 then 'Oui' else 'Non'
	end as cluster
	FROM  prestationsINAMI p
	WHERE 1=1
	ORDER BY p.code+0";
	$rs = $db->query($sql);
	$termOk = "";
	$termNotOk = "";
	$termOksize = 0;
	$termNotOksize = 0;
	while(is_array($row = $db->next_record($rs))){
		//echo '<pre>';
		//print_r($row);
		//echo '</pre>';
		$libelle = utf8_encode($row['libelle']);
		$libelle = str_replace("''","'",$libelle);
		$_SESSION['prestationsINAMI'][$row['id']]['code'] = $row['code'];
		$_SESSION['prestationsINAMI'][$row['id']]['libelle'] = $libelle;
		$_SESSION['prestationsINAMI'][$row['id']]['input_cb'] = $row['input_cb'];
		if(empty($select)){
			$select = $row['id'];
			$termOk .= '<option value="'.$row['id'].'" selected="selected">['.$row['code'].'] '.$row['libelle'].'</option>';
		}else{
			$termOk .= '<option value="'.$row['id'].'">['.$row['code'].'] '.$libelle.'</option>';
		}
		$termOksize++;
		if($row['input_cb'] == '1'){
			$termNotOk .= '<option value="'.$row['id'].'">['.$row['code'].'] '.$libelle.'</option>';
			$termNotOksize++;
		}
	}
}else{
	$termOk = "";
	$termNotOk = "";
	$termOksize = 0;
	$termNotOksize = 0;
	foreach($_SESSION['prestationsINAMI'] as $id=>$inami){
		if(empty($select)){
			$select = $id;
			$termOk .= '<option value="'.$id.'" selected="selected">['.$inami['code'].'] '.$inami['libelle'].'</option>';
		}else{
			$termOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
		}
		$termOksize++;
		if($inami['input_cb'] == 1){
			$termNotOk .= '<option value="'.$id.'">['.$inami['code'].'] '.$inami['libelle'].'</option>';
			$termNotOksize++;
		}
	}
}
if($termOksize < 26){
	$termOksize = 26;
}
$termOkwidth = "auto";
if($termOk == ''){
	$termOkwidth = "100%";
}
if($termNotOksize < 26){
	$termNotOksize = 26;
}
$termNotOkwidth = "auto";
if($termNotOk == ''){
	$termNotOkwidth = "100%";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
   <head>
      <title>DSJ.Web - Filtres infos séjours</title>
      <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	  <link rel="stylesheet" type="text/css" href="css/default.css" />
      <link rel="stylesheet" type="text/css" href="css/skins/<?php echo $_SESSION['skin']; ?>.css" />
      <?php /*<link rel="stylesheet" type="text/css" href="plugins/dhtmlxGrid/codebase/dhtmlxgrid.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_<?php echo $_SESSION['skin']; ?>.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_<?php echo $_SESSION['skin']; ?>.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxTabbar/codebase/dhtmlxtabbar.css"/>*/ ?>
      <script type="text/javascript" src="plugins/jquery/jquery-1.7.js"></script>
      <?php /*<script type="text/javascript" src="js/dsjweb_prototypes.js"></script>*/ ?>
      <script type="text/javascript" src="js/utilities.js"></script>
      <script type="text/javascript" src="js/design.js"></script>
      <?php /*<script type="text/javascript" src="plugins/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/dhtmlxgrid.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/ext/dhtmlxgrid_filter.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/ext/dhtmlxgrid_pgn.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/ext/dhtmlxgrid_splt.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/ext/dhtmlxgrid_srnd.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/dhtmlxgridcell.js"></script>    
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/excells/dhtmlxgrid_excell_link.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxTabbar/codebase/dhtmlxtabbar.js"></script>    
      <script src="plugins/dhtmlxMenu/codebase/dhtmlxcommon.js"></script>
      <script src="plugins/dhtmlxMenu/codebase/dhtmlxmenu.js"></script>
      <script src="plugins/dhtmlxMenu/codebase/ext/dhtmlxmenu_ext.js"></script>*/ ?>
	  <script type="text/javascript">
		function initialize(){
			writetitre("<?php echo $titre; ?>");
			var heightlist = $("#listesblocs").height();
			var newheightlist = heightlist - 83;
			$("#listesblocs").children().height(newheightlist);
			//setStatus(window.top,"loading","Chargement en cours");
			$('.swap').click(function(){
				setStatus(window.top,"loading","Chargement en cours");
				var from = 'termOk';
				var to = 'termNotOk';
				if($(this).hasClass("rl")){ // si colonne non autorisés
					var from = 'termNotOk';
					var to = 'termOk';
				}
				if($(this).hasClass("rl")){  
					var actionajax = "delete";
				}else{
					var actionajax = "insert";
				}
				var data = '';
				$('#'+from+' option:selected').each(function(){
					//alert($(this).text());
					if(data == ''){
						data = 'id=';
					}
					data = data + $(this).val() + ',';
				});
				if(data != ''){
					data = data.slice(0,-1);

				$.post("filtresSejourInfos.php",{action:actionajax,string:data},function(response){
					if(response!=null&&response.status=='success'){
						$('#termNotOk').html(response.termnotok);
						$('#termNotOk').attr('size', response.termnotoksize);
						$('#termNotOk').width('auto');
						var widthauto = $('#termNotOk').width();
						$('#termNotOk').width('100%');
						var width100 = $('#termNotOk').width();
						if(widthauto > width100){
							$('#termNotOk').width('auto');
						}
						setStatus(window.top,"success","Chargement Termin&eacute");
					}
					else if(response!=null&&response.status=='error'){}
					else if(response!=null&&response.status=='info'){}
					else{
						setStatus(window.top,"success","Chargement Termin&eacute");
					}
				},'json');
				} else {
					setStatus(window.top,"success","Chargement Termin&eacute");
					alert("Vous n'avez rien selectionné");
				}
				
			});
			$('.swapfromto').click(function(){
				setStatus(window.top,"loading","Chargement en cours");
				var fromcode = $("#fromcode").val();
				var tocode = $("#tocode").val();
				var from = 'termOk';
				var to = 'termNotOk';
				if($(this).hasClass("rl")){
					from = 'termNotOk';
					to = 'termOk';
				}
				if($(this).hasClass("rl")){
					var actionajax = "delete";
				}else{
					var actionajax = "insert";
				}
				var data = 'fromcode='+fromcode+'&tocode='+tocode;
				$.post("filtresSejourInfos.php",{action:actionajax,string:data},function(response){
					if(response!=null&&response.status=='success'){
						$('#termNotOk').html(response.termnotok);
						$('#termNotOk').attr('size', response.termnotoksize);
						$('#termNotOk').width('auto');
						var widthauto = $('#termNotOk').width();
						$('#termNotOk').width('100%');
						var width100 = $('#termNotOk').width();
						if(widthauto > width100){
							$('#termNotOk').width('auto');
						}
						setStatus(window.top,"success","Chargement Termin&eacute");
					}
					else if(response!=null&&response.status=='error'){}
					else if(response!=null&&response.status=='info'){}
					else{
						setStatus(window.top,"success","Chargement Termin&eacute");
					}
					
					
				},'json');
				
				$("#fromcode").attr('value',"");
				$("#tocode").attr('value',"");
			});
			$(document).keyup(function(event){
                event.stopPropagation();
				switch(event.keyCode){
					case 13 :
						
						//$('#termOk option[value="'+idcode+'"]').prop('selected', true);
						
						setStatus(window.top,"loading","Chargement en cours");
						var data = '';
						$("#searchinput input[type='text']").each(function(){
							if($(this).val() != ''){
								data = data + $(this).attr('id') + '=' + $(this).val() + '&';
							}
						});
						if(data != ''){
							data = data.slice(0,-1);
						}
						$.post("filtresSejourInfos.php",{action:'search',string:data},function(response){
							if(response!=null&&response.status=='success'){
								//alert(response.termOK);
								//alert(response.termok);
								$('#termOk').html(response.termok);
								$('#termOk').attr('size', response.termoksize);
								if(response.termok!=''){
									$('#termOk').width('auto');
								}else{
									$('#termOk').width('100%');
								}
								$('#termOk option').prop('selected', false);
								
								$('#termOk option[value="'+response.select+'"]').prop('selected', true);
								
								//alert($('#termOk option[value="'+response.select+'"]').scrollTop());
								//$('#termOk option[value="'+response.select+'"]').parent().css( "background-color", "red" );
								//var positionlist = $('#termOk option[value="'+response.select+'"]').parent().parent().position();
								
								//alert(positionlist.top);
								//var position = $('#termOk option[value="'+response.select+'"]').position();
								
								//alert(position.top);
								
								$('#termOkContainer').scrollTop(0);
								
								if (!response.termok) { //si la recherche est vide, pas d'exportation excel possible
								$('.excelbutton').hide();
								} else if (response.termok) { //Si la recherche est ok, exportation excel possible
								$('.excelbutton').show();
								}
								
								//$('#termOk option[value="'+response.select+'"]').parent().parent().scrollTop(position.top-positionlist.top);
								
								setStatus(window.top,"success","Chargement Termin&eacute");
								
								
							}
							else if(response!=null&&response.status=='error'){}
							else if(response!=null&&response.status=='info'){}
							else{
								setStatus(window.top,"success","Chargement Termin&eacute");
							}
						},'json');
						//setStatus(window.top,"success","Chargement Termin&eacute");
		
					break;
					
					
				}
			});
			setStatus(window.top,"success","Chargement Termin&eacute");
		}
	  </script>
   </head>
    <body onload="initialize();" style="width:99%; height:98%; overflow: auto;">
		<div class="divFiltresBox" style="float:left; width:100%;height:25px; padding-top:5px;" id="searchinput">
			Recherche par code <input type="text" tabindex="1" id="searchcode" name="searchcode" value="" maxlength="6" size="6">
			Recherche par libellé <input type="text" tabindex="2" id="searchlib" name="searchlib" value="" size="50">
		</div>
		<div class="divFiltresBox" style="float:left; width:100%;height:25px; padding-top:5px;text-align:center;">
			<input type="text" tabindex="3" id="fromcode" name="fromcode" value="" style="width:60px;">
			<button type="button" tabindex="5" title="Transférer les prestations" class="btn btn-secondary swapfromto rl"><<</button>
			<button type="button" tabindex="6" title="Transférer les prestations" class="btn btn-secondary swapfromto lr">>></button>
			<input type="text" tabindex="4" id="tocode" name="tocode" value="" style="width:60px;">
		</div>
		<div>
			<div style="float: left; width: 46%">
			 <div class='divFiltresBox' style='width:100%;height:25px; padding-top:5px;'>Disponibles <form style='display:inline-block; width:16px; height:16px;' method='POST' action='convertToCSV.php'><input type='hidden' name='disponibles'/><input type='image' src='images/page_white_excel.png' class="excelbutton" alt='Exporter' title='Exporter' width='16px;' height='16px;' border='0'></form></div>
			</div>
			<div style="float: left; width: 8%">
				<div class="divFiltresBox" style="width:100%;height:25px; padding-top:5px;text-align: center;">
					<button type="button" title="Transférer les prestations sélectionnées" class="btn btn-secondary swap rl"><</button>
					<button type="button" title="Transférer les prestations sélectionnées" class="btn btn-secondary swap lr">></button>
				</div>
			</div>
			<div style="float: left; width: 46%">
				<div class="divFiltresBox" style="width:100%;height:25px; padding-top:5px;">Non autorisés <form style='display:inline-block; width:16px; height:16px;' method="POST" action="convertToCSV.php"><input type='hidden' name='non_autorisés'/><input type="image" src="images/page_white_excel.png" class="excelbutton" alt="Exporter" title="Exporter" width="16px;" height="16px;" border="0"></form></div>
			</div>
		</div>
		<div id="listesblocs" style="height:100%;">
			<div style="float: left; width: 50%;height: 437px;">
				<div id="termOkContainer" style="display inline-block;overflow-x:scroll;height: 100%;">
					<select id="termOk" name="termOk[]" multiple size="<?php echo $termOksize; ?>" style="width:<?php echo $termOkwidth;?>;padding-bottom:300px;">
						<?php echo $termOk; ?>
					</select>
				</div>
			</div>
			<div style="float: left; width: 50%;height: 437px;">
				<div style="display inline-block;overflow-x:scroll;height: 100%;">
					<select id="termNotOk" name="termNotOk[]" multiple size="<?php echo $termNotOksize; ?>" style="width:<?php echo $termNotOkwidth; ?>;padding-bottom:300px;">
						<?php echo $termNotOk; ?>
					</select>
				</div>
			</div>
		</div>
        <div id="divLoadingIcon" style="display:none; position:absolute; top:0;left:0; z-index:50;"><img src="images/loading.gif"/></div>
        <div id="divHelp" style="position:absolute; z-index: 2000; display:none; background-color: #feffe5; font-weight: bold; border:solid 1px #a4bed4; color:#a4bed4;">
            <div style="float:left;">
                <img src="images/help_1.png" />
            </div>
            <div style="float:left; margin-left: 5px; margin-right: 5px;">
                Pressez sur entr&eacute;e pour filtrer
            </div>
        </div>
    </body>
</html>