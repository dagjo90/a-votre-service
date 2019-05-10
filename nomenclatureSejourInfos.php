<?php 
require_once("include/fct.php");
require_once("plugins/xajax/xajax_core/xajax.inc.php");
require_once("classes/query.class");

valideuser();

if(!isset($_REQUEST['xjxfun'])){
    $db = new dbdsjweb();
    $db->connect() or exit("no DB connection !");
    $sql = 'SELECT * from sejour_lock WHERE id_user='.$_SESSION['user_id'];
    $rs = $db->query($sql);
    if(is_array($row = $db->next_record($rs))){
        $sql = 'DELETE sejour_lock WHERE id_user='.$_SESSION['user_id'];
        $db->query($sql);
    }
    $db->disconnect();
}

$titre='<h1>Nomenclatures des infos complémentaires pour le séjour</h1>';
$_SESSION['vsid']=null;


/* Préparation des données pour la grille */
$dataDescription = array(
    0=>array('field'=>'p.id',
        'type_data'=>'numeric',
        'id'=>'id',
        'label'=>'key',
        'width'=>'0',
        'align'=>'left',
        'type_cell'=>'ro',
        'sort'=>'na',
        'header'=>'#text_filter',
        'links'=>'',
        'in_query'=>1),
    1=>array('field'=>'p.code',
        'type_data'=>'varchar',
        'id'=>'code',
        'label'=>'Code',
        'width'=>'10',
        'align'=>'left',
        'type_cell'=>'ro',
        'sort'=>'server',
        'header'=>'#text_filter',
        'links'=>'',
        'in_query'=>1),
    2=>array('field'=>'p.libelle',
        'type_data'=>'varchar',
        'id'=>'libelle',
        'label'=>'Libellé',
        'width'=>'*',
        'align'=>'left',
        'type_cell'=>'ro',
        'sort'=>'server',
        'header'=>'#text_filter',
        'links'=>'',
        'in_query'=>1)
);

            
function buildQuery($_index,$_direction,$_filtre,$_recordIndex,$_cptRecords,$_typeData){
    global $dataDescription;
    $reponse = new xajaxResponse();   
    
    $db = new dbdsjweb();
    $db->connect() or exit("no DB connection !");
    
    /* Tri */
    $fieldOrderIndex = '';
    if(!empty($dataDescription[$_index]['field'])){
        $fieldOrderIndex = $dataDescription[$_index]['field'];
    }else{
        $fieldOrderIndex = $dataDescription[1]['field'];
    }
    
    /* corp de la requête */
    $q = new query();
    $selectString = '';
    $nbFields = count($dataDescription);
    for($i=0;$i<$nbFields;$i++){
        if($dataDescription[$i]['in_query']==1){
            if(!empty($selectString))
                $selectString .= ',';
            if($i == 1){
                $dataDescription[$i]['field'] = str_replace('{table_name}', $_typeData, $dataDescription[$i]['field']);
            }
            $selectString .= $dataDescription[$i]['field'].' as '.$dataDescription[$i]['id'];
        }
    }
    $q->setSelect($selectString);
    
    switch($_typeData){
        case 'prestations':
            $q->setFrom(' prestationsINAMI p ');
            break;
        case 'labo':
            $q->setFrom(' labo p ');
            break;
        case 'pharma':
            $q->setFrom(' pharma p ');
            break;
    }
    
    
    $orderClause = '';
    $q->setWhere("1=1");
    /* Déterminer le filtre (les champs de recherche) */
    if($_filtre['param']){
        $tabKeys = array_keys($_filtre['param']);
        $cptKeys = count($tabKeys);
        for($i=1;$i<$cptKeys;$i++){
            if($_filtre['param'][$i] == '__/__/____'){
                $_filtre['param'][$i] = '';
            }
            if(!empty($_filtre['param'][$i])){
                
                $field = $dataDescription[$tabKeys[$i]]['field'];
                
                if(!empty($field)){
                    
                    if($field != $fieldOrderIndex){
                        if(empty($orderClause)){
                            $orderClause = $field.' ASC ';
                        }else{
                            $orderClause .= ','.$field.' ASC ';
                        }
                    }
                    
                    $firstDelimiter = '';
                    $lastDelimiter = '';
                    
                    switch($dataDescription[$tabKeys[$i]]['type_data']){                 
                        case 'date':
                            $dte=str_replace("/","-",reverse_date($_filtre['param'][$tabKeys[$i]],'F'));
                            $q->setWhere($q->getWhere()." and date_to_char(YYYY-MM-DD,".$field.")>='".$dte."' and date_to_char(YYYY-MM-DD,".$field.")<='".$dte."' ");
                            break;
                        case 'char':
                            $firstDelimiter = '\'';
                            $lastDelimiter = '\'';
                            $q->setWhere($q->getWhere().' AND '.$field.' = '.$firstDelimiter.$_filtre['param'][$tabKeys[$i]].$lastDelimiter);
                            break;
                        case 'varchar':
                            $firstDelimiter = '\'';
                            $lastDelimiter = '%\'';
                            $q->setWhere($q->getWhere().' AND '.$field.' LIKE '.$firstDelimiter.$_filtre['param'][$tabKeys[$i]].$lastDelimiter);
                            break;
                        default :
                            $q->setWhere($q->getWhere().' AND '.$field.' = '.$firstDelimiter.$_filtre['param'][$tabKeys[$i]].$lastDelimiter);
                            break;
                    }
                }
            }
        }
    }
    
    if(empty($_direction)){
        $_direction = 'ASC';
    }else{
        if($_direction=='des')
            $_direction = 'DESC';
    }
    
    if(!empty($fieldOrderIndex)){
        if(!empty($orderClause))
            $orderClause= $fieldOrderIndex.' '.$_direction.','.$orderClause.' ';
        else
            $orderClause= $fieldOrderIndex.' '.$_direction;
    }
    
    $q->setOrderBy($orderClause);
    
    /*if(!isset($_SESSION['orderListeMedSec'])){
        // Aucun enregistrement précédent => enregistre les filtres
        $_SESSION['orderListeMedSec'] = $orderClause;
        $_SESSION['orderIndexListeMedSec'] = $_index;
        $_SESSION['orderDirectionListeMedSec'] = $_direction;
    }else{
        if((isset($_filtre['origin']))&&($_filtre['origin']!='self')){
            // Provenance d'une autre page => récupère les parmètres et filtres
            $q->setOrderBy($_SESSION['orderListeMedSec']);
        }else{
            if(!isset($_filtre['origin'])){
                $q->setOrderBy($_SESSION['orderListeMedSec']);
            }else{
                $_SESSION['orderListeMedSec'] = $orderClause;
                $_SESSION['orderIndexListeMedSec'] = $_index;
                $_SESSION['orderDirectionListeMedSec'] = $_direction;
            }
        }
    }*/
    
    $filtreArray = false;
    $rawRecords = array();
    $nextRecordIndex = 0;
    
    $rawRecords=$db->queryToArray($q,$_recordIndex,$_cptRecords,array('id'),array(),$dataDescription);
    $reponse->script('document.getElementById("hHasNext_'.$_typeData.'").value = '.$rawRecords['hasNext']);
    $reponse->script('document.getElementById("hNextStart_'.$_typeData.'").value = '.$rawRecords['nextStart']);
    unset($rawRecords['hasNext']);
    unset($rawRecords['nextStart']);
    
    $rawRecords['headers'] = array();
    $rawRecords['type'] = array();
    $rawRecords['links'] = array();
    for($i=0;$i<count($dataDescription);$i++){
        array_push($rawRecords['headers'],$dataDescription[$i]['id']);
        array_push($rawRecords['type'],$dataDescription[$i]['type_data']);
        array_push($rawRecords['links'],$dataDescription[$i]['links']);
    }
    
    $_SESSION['tab'] = serialize($rawRecords);
    
    $db->disconnect(); 
    return $reponse;
}

$xajax = new xajax();
$xajax->register(XAJAX_FUNCTION,'buildQuery',array('mode'=>"'synchronous'"));
$xajax->processRequest();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
   <head>
      <title>DSJ.Web - Filtres infos séjours</title>
      <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" type="text/css" href="css/default.css" />
      <link rel="stylesheet" type="text/css" href="css/skins/<?php echo $_SESSION['skin']; ?>.css" />
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxGrid/codebase/dhtmlxgrid.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxGrid/codebase/skins/dhtmlxgrid_dhx_<?php echo $_SESSION['skin']; ?>.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxMenu/codebase/skins/dhtmlxmenu_dhx_<?php echo $_SESSION['skin']; ?>.css"/>
      <link rel="stylesheet" type="text/css" href="plugins/dhtmlxTabbar/codebase/dhtmlxtabbar.css"/>
      <script type="text/javascript" src="plugins/jquery/jquery-1.7.js"></script>
      <script type="text/javascript" src="js/dsjweb_prototypes.js"></script>
      <script type="text/javascript" src="js/utilities.js"></script>
      <script type="text/javascript" src="js/design.js"></script>
      <script type="text/javascript" src="plugins/dhtmlxGrid/codebase/dhtmlxcommon.js"></script>
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
      <script src="plugins/dhtmlxMenu/codebase/ext/dhtmlxmenu_ext.js"></script>
        <?php
            $xajax->printJavascript("plugins/xajax/");//Fonction qui va afficher le javascript de la page
        ?>
      <script type="text/javascript">
        var filtre = new Array();
        var sortIndex = 3;
        var sortDirection = "asc";
        var startIndex = 0;
        var nbreLignes = 0;
        var resizeTimeout;
        var pageLaboRecordsIndexes = new Array();
        pageLaboRecordsIndexes[0] = 0;
        var pagePharmaRecordsIndexes = new Array();
        pagePharmaRecordsIndexes[0] = 0;
        var pagePrestationsRecordsIndexes = new Array();
        pagePrestationsRecordsIndexes[0] = 0;
        
            
        <?php 
            $js_array = json_encode($dataDescription);
            echo "var gridColumns = ". $js_array . ";\n";
        ?>
            
        <?php if(isset($_SESSION["orderIndexListePrestations"])){ ?>
            var sessionOrderIndexListePrestations = "<?php echo $_SESSION["orderIndexListePrestations"]; ?>";
            var sessionOrderDirectionListePrestations = "<?php echo $_SESSION["orderDirectionListePrestations"]; ?>";
        <?php }else{ ?>
            var sessionOrderIndexListePrestations = "";
            var sessionOrderDirectionListePrestations = "";
        <?php } ?>
            
        <?php if(isset($_SESSION["orderIndexListePharma"])){ ?>
            var sessionOrderIndexListePharma = "<?php echo $_SESSION["orderIndexListePharma"]; ?>";
            var sessionOrderDirectionListePharma= "<?php echo $_SESSION["orderDirectionListePharma"]; ?>";
        <?php }else{ ?>
            var sessionOrderIndexListePharma = "";
            var sessionOrderDirectionListePharma = "";
        <?php } ?>
            
        <?php if(isset($_SESSION["orderIndexListeLabo"])){ ?>
            var sessionOrderIndexListeLabo = "<?php echo $_SESSION["orderIndexListeLabo"]; ?>";
            var sessionOrderDirectionListeLabo= "<?php echo $_SESSION["orderDirectionListeLabo"]; ?>";
        <?php }else{ ?>
            var sessionOrderIndexListeLabo = "";
            var sessionOrderDirectionListeLabo = "";
        <?php } ?>
        
        function filterGrid(dataType){
            setStatus(window.top,"loading","Chargement en cours");
            xajax_buildQuery(sortIndex,sortDirection,filtre,startIndex,nbreLignes,dataType);
            refreshGrid(0,nbreLignes,dataType);
            if(document.getElementById("hHasNext_"+dataType).value == "0"){
                document.getElementById("btnNext_"+dataType).style.display = 'none';
            }else{
                document.getElementById("btnNext_"+dataType).style.display = '';
            }
            document.getElementById("btnPrevious_"+dataType).style.display = 'none';
        }
        
        function getGridParameters(parameter){
            var str = gridColumns[0][parameter];
            var size = Object.size(gridColumns);
            for(var i=1;i<size;i++){
                str += ","+gridColumns[i][parameter];
            }
            return str;
        }
        
        function getColumnIndex(columnId){
            var size = Object.size(gridColumns);
            for(var i=0;i<size;i++){
                if(gridColumns[i]['id']==columnId)
                    return i;
            }
            return -1;
        }
        
        function getColumnsIndexByDataType(dataType){
            var size = Object.size(gridColumns);
            var returnArray = new Array();
            for(var i=0;i<size;i++){
                if(gridColumns[i]['type_data']==dataType)
                    returnArray.push(i);
            }
            return returnArray;
        }
        
        function initialize(){
            $("div").on("keypress",function(e){
                if(e.keyCode==32){
                    return false;
                }
            });
            writetitre("<?php echo $titre?>");
            setStatus(window.top,"loading","Chargement en cours");
            window.focus();
            
            tabbar = new dhtmlXTabBar("tabbar");
            tabbar.setSkin('dhx_<?php echo $_SESSION['skin']; ?>');
            tabbar.setImagePath("plugins/dhtmlxTabbar/codebase/imgs/");
            tabbar.addTab("prestations", "Prestations INAMI", "150px");
            tabbar.addTab("pharma", "Pharmacie", "150px");
            tabbar.addTab("labo", "Labo", "150px");
            tabbar.setContent("prestations","tabPrestations");
            tabbar.setContent("pharma","tabPharma");
            tabbar.setContent("labo","tabLabo");
        
            nbreLignes = 20;
            gridPrestations = new dhtmlXGridObject("divGridPrestations");
            gridPrestations.setImagePath("plugins/dhtmlxGrid/codebase/imgs/");
            gridPrestations.setHeader(getGridParameters('label'));
            gridPrestations.setInitWidthsP(getGridParameters('width'));
            gridPrestations.setColAlign(getGridParameters('align'));
            gridPrestations.setColTypes(getGridParameters('type_cell'));
            gridPrestations.setSkin("dhx_<?php echo $_SESSION['skin']; ?>");
            gridPrestations.setColSorting(getGridParameters('sort'));
            gridPrestations.attachHeader(getGridParameters('header'));
            gridPrestations.attachEvent("onBeforeSorting",sortGrid);
            gridPrestations.attachEvent("onFilterStart", function(indexes,values){
                return false;
            });    
            
            gridPrestations.enableAutoHeigth(true);
            /*grid.attachEvent("onXLE", function(grid_obj,count){
                var rows = grid.getAllRowIds();
                var tabRows = rows.split(",");
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsSaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsSaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsUnsaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsUnsaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
            });*/
            gridPrestations.init();
            gridPrestations.clearAll();
            
            gridPharma = new dhtmlXGridObject("divGridPharma");
            gridPharma.setImagePath("plugins/dhtmlxGrid/codebase/imgs/");
            gridPharma.setHeader(getGridParameters('label'));
            gridPharma.setInitWidthsP(getGridParameters('width'));
            gridPharma.setColAlign(getGridParameters('align'));
            gridPharma.setColTypes(getGridParameters('type_cell'));
            gridPharma.setSkin("dhx_<?php echo $_SESSION['skin']; ?>");
            gridPharma.setColSorting(getGridParameters('sort'));
            gridPharma.attachHeader(getGridParameters('header'));
            gridPharma.attachEvent("onBeforeSorting",sortGrid);
            gridPharma.attachEvent("onFilterStart", function(indexes,values){
                return false;
            });
      
            gridPharma.enableAutoHeigth(true);
            /*grid.attachEvent("onXLE", function(grid_obj,count){
                var rows = grid.getAllRowIds();
                var tabRows = rows.split(",");
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsSaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsSaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsUnsaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsUnsaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
            });*/
            gridPharma.init();
            gridPharma.clearAll();
            
            gridLabo = new dhtmlXGridObject("divGridLabo");
            gridLabo.setImagePath("plugins/dhtmlxGrid/codebase/imgs/");
            gridLabo.setHeader(getGridParameters('label'));
            gridLabo.setInitWidthsP(getGridParameters('width'));
            gridLabo.setColAlign(getGridParameters('align'));
            gridLabo.setColTypes(getGridParameters('type_cell'));
            gridLabo.setSkin("dhx_<?php echo $_SESSION['skin']; ?>");
            gridLabo.setColSorting(getGridParameters('sort'));
            gridLabo.attachHeader(getGridParameters('header'));
            gridLabo.attachEvent("onBeforeSorting",sortGrid);
            gridLabo.attachEvent("onFilterStart", function(indexes,values){
                return false;
            });
              
            gridLabo.enableAutoHeigth(true);
            /*grid.attachEvent("onXLE", function(grid_obj,count){
                var rows = grid.getAllRowIds();
                var tabRows = rows.split(",");
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsSaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsSaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
                for(var i=0;i<tabRows.length;i++){
                    for(var j=0;j<tabMedecinsUnsaved.length;j++){
                        if(grid.cells(tabRows[i],0).getValue()==tabMedecinsUnsaved[j]){
                            grid.cells(tabRows[i],1).setValue(1);
                        }
                    }
                }
            });*/
            gridLabo.init();
            gridLabo.clearAll();

            filtre["param"] = new Array();
            
            xajax_buildQuery(sortIndex,sortDirection,filtre,startIndex,nbreLignes,"prestations");
            refreshGrid(0,nbreLignes,"prestations");
            
            xajax_buildQuery(sortIndex,sortDirection,filtre,startIndex,nbreLignes,"labo");
            refreshGrid(0,nbreLignes,"labo");
            
            xajax_buildQuery(sortIndex,sortDirection,filtre,startIndex,nbreLignes,"pharma");
            refreshGrid(0,nbreLignes,"pharma");
                        
            tabbar.setTabActive("prestations");
            
            if(document.getElementById("hHasNext_prestations").value == "0"){
                document.getElementById("btnNext_prestations").style.display = 'none';
            }else{
                document.getElementById("btnNext_prestations").style.display = '';
            }
            document.getElementById("btnPrevious_prestations").style.display = 'none';
            
            if(document.getElementById("hHasNext_labo").value == "0"){
                document.getElementById("btnNext_labo").style.display = 'none';
            }else{
                document.getElementById("btnNext_labo").style.display = '';
            }
            document.getElementById("btnPrevious_labo").style.display = 'none';
            
            if(document.getElementById("hHasNext_pharma").value == "0"){
                document.getElementById("btnNext_pharma").style.display = 'none';
            }else{
                document.getElementById("btnNext_pharma").style.display = '';
            }
            document.getElementById("btnPrevious_pharma").style.display = 'none';
            
            
            $(document).keyup(function(event) {
                event.stopPropagation();
                /*if(event.ctrlKey){                    
                    switch(event.keyCode){
                        case 77: // lettre M
                            var frameId = $("#hFrameId").val();
                            minusPopup(window.top.document,frameId);
                        break;
                        case 81: // lettre Q 
                            var frameId = $("#hFrameId").val();
                            closePopup(window.top.document,frameId);
                        break;

                    }
                }*/
                switch(event.keyCode){
                    case 13 :
                        var elem = "";
                        switch(tabbar.getActiveTab()){
                            case "labo":
                                elem = "divGridLabo";
                                break;
                            case "pharma":
                                elem = "divGridPharma";
                                break;
                            case "prestations":
                                elem = "divGridPrestations";
                                break;
                        }
                        filtre["origin"] = "self";
                        startIndex = 0;
                        filtre["param"][0] = "";
                        filtre["param"][1] = "";
                        $("#"+elem+" .hdrcell input[type='text']").each(function(index,elem){
                            if($(this).val()!="")
                                filtre["param"][index] = $(this).val();
                            else
                                filtre["param"][index] = "";                                
                        });
                        filterGrid(tabbar.getActiveTab());
                    break;
                    case 40: // Arrow down
                        var grid
                        switch(tabbar.getActiveTab()){
                            case "labo":
                                grid = gridLabo;
                                break;
                            case "pharma":
                                grid = gridPharma;
                                break;
                            case "prestations":
                                grid = gridPrestations;
                                break;
                        }
                        if(grid.getSelectedId()==null){
                            grid.selectRow(0,true,false,false);
                            grid.obj.rows[1].cells[0].focus();
                        }
                    break;
                }
            });
            
            $("#btnSubmit").on("click",function(){
                return false;
            });
            
            $(document.getElementById("btnNext_prestations")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                
                document.getElementById("hPageId_prestations").value = parseInt(document.getElementById("hPageId_prestations").value)+1;
                pagePrestationsRecordsIndexes[document.getElementById("hPageId_prestations").value] = document.getElementById("hNextStart_prestations").value;

                xajax_buildQuery(sortIndex,sortDirection,filtre,document.getElementById("hNextStart_prestations").value,nbreLignes,"prestations");
                refreshGrid(document.getElementById("hNextStart_prestations").value,nbreLignes,"prestations");
                                
                if(document.getElementById("hHasNext_prestations").value == "0"){
                    document.getElementById("btnNext_prestations").style.display = 'none';
                }else{
                    document.getElementById("btnNext_prestations").style.display = '';
                }
                
                if(parseInt(document.getElementById("hPageId_prestations").value)>=2){
                    document.getElementById("hPreviousStart_prestations").value = pagePrestationsRecordsIndexes[document.getElementById("hPageId_prestations").value-2];
                }                
                if(document.getElementById("hPageId_prestations").value == 0){
                    document.getElementById("btnPrevious_prestations").style.display = 'none';
                }else{
                    document.getElementById("btnPrevious_prestations").style.display = '';
                }
            });
            
            $(document.getElementById("btnPrevious_prestations")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                xajax_buildQuery(sortIndex,sortDirection,filtre,pagePrestationsRecordsIndexes[document.getElementById("hPageId_prestations").value-1],nbreLignes,"prestations");
                refreshGrid(document.getElementById("hNextStart_prestations").value,nbreLignes,"prestations");
                document.getElementById("hPageId_prestations").value = parseInt(document.getElementById("hPageId_prestations").value)-1 ;
                if(document.getElementById("hPageId_prestations").value == 0){
                    document.getElementById("btnPrevious_prestations").style.display = 'none';
                }
                if(parseInt(document.getElementById("hPageId_prestations").value)>=2){
                    document.getElementById("hPreviousStart_prestations").value = pagePrestationsRecordsIndexes[document.getElementById("hPageId_prestations").value-2];
                }
                if(((parseInt(document.getElementById("hPageId_prestations").value)+1)*nbreLignes)>parseInt(document.getElementById("hTotalRecords_prestations").value)){
                    document.getElementById("btnNext_prestations").style.display = 'none';
                }else{
                    document.getElementById("btnNext_prestations").style.display = '';
                }
            });
            
            $(document.getElementById("btnNext_labo")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                
                document.getElementById("hPageId_labo").value = parseInt(document.getElementById("hPageId_labo").value)+1;
                pageLaboRecordsIndexes[document.getElementById("hPageId_labo").value] = document.getElementById("hNextStart_labo").value;

                xajax_buildQuery(sortIndex,sortDirection,filtre,document.getElementById("hNextStart_labo").value,nbreLignes,"labo");
                refreshGrid(document.getElementById("hNextStart_labo").value,nbreLignes,"labo");
                                
                if(document.getElementById("hHasNext_labo").value == "0"){
                    document.getElementById("btnNext_labo").style.display = 'none';
                }else{
                    document.getElementById("btnNext_labo").style.display = '';
                }
                
                if(parseInt(document.getElementById("hPageId_labo").value)>=2){
                    document.getElementById("hPreviousStart_labo").value = pageLaboRecordsIndexes[document.getElementById("hPageId_labo").value-2];
                }                
                if(document.getElementById("hPageId_labo").value == 0){
                    document.getElementById("btnPrevious_labo").style.display = 'none';
                }else{
                    document.getElementById("btnPrevious_labo").style.display = '';
                }
            });
            
            $(document.getElementById("btnPrevious_labo")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                xajax_buildQuery(sortIndex,sortDirection,filtre,pageLaboRecordsIndexes[document.getElementById("hPageId_labo").value-1],nbreLignes,"labo");
                refreshGrid(document.getElementById("hNextStart_labo").value,nbreLignes,"labo");
                document.getElementById("hPageId_labo").value = parseInt(document.getElementById("hPageId_labo").value)-1 ;
                if(document.getElementById("hPageId_labo").value == 0){
                    document.getElementById("btnPrevious_labo").style.display = 'none';
                }
                if(parseInt(document.getElementById("hPageId_labo").value)>=2){
                    document.getElementById("hPreviousStart_labo").value = pageLaboRecordsIndexes[document.getElementById("hPageId_labo").value-2];
                }
                if(((parseInt(document.getElementById("hPageId_labo").value)+1)*nbreLignes)>parseInt(document.getElementById("hTotalRecords_labo").value)){
                    document.getElementById("btnNext_labo").style.display = 'none';
                }else{
                    document.getElementById("btnNext_labo").style.display = '';
                }
            });
            
            $(document.getElementById("btnNext_pharma")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                
                document.getElementById("hPageId_pharma").value = parseInt(document.getElementById("hPageId_pharma").value)+1;
                pagePharmaRecordsIndexes[document.getElementById("hPageId_pharma").value] = document.getElementById("hNextStart_pharma").value;

                xajax_buildQuery(sortIndex,sortDirection,filtre,document.getElementById("hNextStart_pharma").value,nbreLignes,"pharma");
                refreshGrid(document.getElementById("hNextStart_pharma").value,nbreLignes,"pharma");
                                
                if(document.getElementById("hHasNext_pharma").value == "0"){
                    document.getElementById("btnNext_pharma").style.display = 'none';
                }else{
                    document.getElementById("btnNext_pharma").style.display = '';
                }
                
                if(parseInt(document.getElementById("hPageId_pharma").value)>=2){
                    document.getElementById("hPreviousStart_pharma").value = pagePharmaRecordsIndexes[document.getElementById("hPageId_pharma").value-2];
                }                
                if(document.getElementById("hPageId_pharma").value == 0){
                    document.getElementById("btnPrevious_pharma").style.display = 'none';
                }else{
                    document.getElementById("btnPrevious_pharma").style.display = '';
                }
            });
            
            $(document.getElementById("btnPrevious_pharma")).on("click", function(e){
                setStatus(window.top,"loading","Chargement en cours");
                
                xajax_buildQuery(sortIndex,sortDirection,filtre,pagePharmaRecordsIndexes[document.getElementById("hPageId_pharma").value-1],nbreLignes,"pharma");
                refreshGrid(document.getElementById("hNextStart_pharma").value,nbreLignes,"pharma");
                document.getElementById("hPageId_pharma").value = parseInt(document.getElementById("hPageId_pharma").value)-1 ;
                if(document.getElementById("hPageId_pharma").value == 0){
                    document.getElementById("btnPrevious_pharma").style.display = 'none';
                }
                if(parseInt(document.getElementById("hPageId_pharma").value)>=2){
                    document.getElementById("hPreviousStart_pharma").value = pagePharmaRecordsIndexes[document.getElementById("hPageId_pharma").value-2];
                }
                if(((parseInt(document.getElementById("hPageId_pharma").value)+1)*nbreLignes)>parseInt(document.getElementById("hTotalRecords_pharma").value)){
                    document.getElementById("btnNext_pharma").style.display = 'none';
                }else{
                    document.getElementById("btnNext_pharma").style.display = '';
                }
            });
        }
        
        /* Trie la grille selon l'indice de la colonne voulue */
        function sortGrid(ind,gridObj,direct,recordIndex,cptRecords){
            var typeData = "";
            var grid;
            if(gridObj == gridPrestations){
                typeData = "prestations";
                grid = gridPrestations;
            }else{
                if(gridObj == gridLabo){
                    typeData = "labo";
                    grid = gridLabo;
                }else{
                    if(gridObj == gridPharma){
                        typeData = "pharma";
                        grid = gridPharma;
                    }
                }
            }
            filtre["origin"] = "self";
            document.getElementById("hPageId_"+typeData).value = 0;
            if(recordIndex==null)recordIndex=0;
            if(cptRecords==null)cptRecords=nbreLignes;
            sortIndex = ind;
            sortDirection = direct;
            
            setStatus(window.top,"loading","Chargement en cours");
            xajax_buildQuery(ind,direct,filtre,recordIndex,cptRecords,typeData);
            refreshGrid(0,nbreLignes,typeData);
            if(document.getElementById("hHasNext_"+typeData).value == "0"){
                document.getElementById("btnNext_"+typeData).style.display = 'none';
            }else{
                document.getElementById("btnNext_"+typeData).style.display = '';
            }
            document.getElementById("btnPrevious_"+typeData).style.display = 'none';
            grid.setSortImgState(true,ind,direct);
            return false;
        }
        
        
        function refreshGrid(posStart,count,typeData){
            var grid;
            var sessionOrderIndex;
            var sessionOrderDirection;
            if(typeData == "prestations"){
                grid = gridPrestations
                sessionOrderIndex = sessionOrderIndexListePrestations;
                sessionOrderDirection = sessionOrderDirectionListePrestations;
            }
            if(typeData == "pharma"){
                grid = gridPharma;
                sessionOrderIndex = sessionOrderIndexListePharma;
                sessionOrderDirection = sessionOrderDirectionListePharma;
            }
            if(typeData == "labo"){
                grid = gridLabo;
                sessionOrderIndex = sessionOrderIndexListeLabo;
                sessionOrderDirection = sessionOrderDirectionListeLabo;
            }
            grid.clearAll();
            if(sessionOrderIndex != ""){
                sortIndex = sessionOrderIndex;
                sortDirection = sessionOrderDirection;
            }
            grid.setSortImgState(true,sortIndex,sortDirection);
            var param="";
            grid.loadXML("plugins/connectors/grid_connector_array.php?vsid=tab"+param,function(){
                setStatus(window.top,"success","Chargement Termin&eacute");
            });
            
        }
        
        /*function computeGridSize(){
            nbreLignes = getNbreRows(document,true,true,true);
            grid.enablePaging(true,nbreLignes,10,"divGridPagingArea",true,"divGridInfoArea");
            grid.setPagingSkin("bricks");
            top.document.getElementById("divDebug").innerHTML += nbreLignes;
            var uid = document.getElementById("hUserId").value;
            setStatus(window.top,"loading","Chargement en cours");
            xajax_buildQuery(sortIndex,sortDirection,filtre,startIndex,nbreLignes,uid);
            if(document.getElementById("hHasNext").value == "0"){
                document.getElementById("btnNext").style.display = 'none';
            }else{
                document.getElementById("btnNext").style.display = '';
            }
            document.getElementById("btnPrevious").style.display = 'none';
        }*/
      </script>
   </head>
    <body onload="initialize();" style="width:99%; height:98%; overflow: auto;">
        <button id="btnSubmit" style="position:absolute;left:-50px; width:1px; height: 1px;"></button>
        <div id="tabbar" align="left" style="width:100%; height:100%; clear:both; overflow:auto;" >
            <div id="tabPrestations" style="padding:10px; overflow:auto;">
                <div id="divGridPrestations" style="width:99%; height:310px;"></div>
                <div id="divNavigationPrestations">
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnPrevious_prestations" style="display:none;">Pr&eacute;c&eacute;dent</button>
                    </div>
                    <div style="float:left">&nbsp;</div>
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnNext_prestations">Suivant</button>
                    </div>
                </div>
            </div>
            <div id="tabPharma" style="padding:10px;">
                <div id="divGridPharma" style="width:99%; height:310px;"></div>
                <div id="divNavigationPharma">
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnPrevious_pharma" style="display:none;">Pr&eacute;c&eacute;dent</button>
                    </div>
                    <div style="float:left">&nbsp;</div>
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnNext_pharma">Suivant</button>
                    </div>
                </div>
            </div>
            <div id="tabLabo" style="padding:10px;">
                <div id="divGridLabo" style="width:99%; height:310px;"></div>
                <div id="divNavigationLabo">
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnPrevious_labo" style="display:none;">Pr&eacute;c&eacute;dent</button>
                    </div>
                    <div style="float:left">&nbsp;</div>
                    <div style="float:left; text-align: right;">
                        <button class="btnText" id="btnNext_labo">Suivant</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="divSearch" style="background-color: #fff; border: solid 1px #ccc; display:none; width:600px; position:absolute; top:0;left:0; z-index:50;"></div>
        <div id="divLoadingIcon" style="display:none; position:absolute; top:0;left:0; z-index:50;"><img src="images/loading.gif"/></div>
        <div id="divDebug" style="display:none"></div>
        <input id="hNextStart_prestations" type="hidden" />
        <input id="hTotalRecords_prestations" type="hidden" />
        <input id="hHasNext_prestations" type="hidden" />
        <input id="hPageId_prestations" type="hidden" value="0" />
        <input id="hPreviousStart_prestations" type="hidden" value="0" />
        <input id="hNextStart_pharma" type="hidden" />
        <input id="hTotalRecords_pharma" type="hidden" />
        <input id="hHasNext_pharma" type="hidden" />
        <input id="hPageId_pharma" type="hidden" value="0" />
        <input id="hPreviousStart_pharma" type="hidden" value="0" />
        <input id="hNextStart_labo" type="hidden" />
        <input id="hTotalRecords_labo" type="hidden" />
        <input id="hHasNext_labo" type="hidden" />
        <input id="hPageId_labo" type="hidden" value="0" />
        <input id="hPreviousStart_labo" type="hidden" value="0" />
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