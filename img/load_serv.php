
<?
include_once ("../js/func.php");
include_once ("../js/cambiocaracteres.php");
include_once ("../js/globalinc.php");
include_once("../js/class.php");
$tipc = TNet::tipus($emurl, 'empid');
if($tipc == " | DEMO "){ $conn = TNet::conectarD(); }else{ $conn = TNet::conectar(); }
$rootdir = "//www.turnonet.com/";
?>
<script>
//menu
function mainmenu(){
// Oculto los submenus
$("#hnavm ul").css({display: "none"});
// Defino que submenus deben estar visibles cuando se pasa el mouse por encima
$("#hnavm li").hover(function(){
$(this).find('ul:first:hidden').css({visibility: "visible",display: "none"}).slideDown(400);
},function(){
$(this).find('ul:first').slideUp(400);
});
}
//menu
mainmenu();

// BORRO de SERVICIOS
function delserv(empid,valor){
if(valor!=""){
if($('#set_serv_sel').length){
var servis = $('#set_serv_sel').val();
servis = " "+servis+" ";
servis = servis.replace(" "+valor+"-", "");
servis = servis.replace("-"+valor+" ", "");
servis = servis.replace("-"+valor+"-", "-");
servis = servis.replace(" "+valor+" ", "");

var addserv = $.trim(servis);
$('#set_serv_sel').val(addserv);
if(addserv == ""){addserv= 0;}

var reff = "//www.turnonet.com/e/esn/servs/"+empid+"/"+addserv;
$("#addeds").load(reff,function(){
$('#addeds').fadeIn('slow');
});
}
}
return false;
};
</script>
<?
if($iserv==0){
echo "<span>No ten&eacute;s servicios seleccionados</span>";
    //$rowstxt = "0 servicios seleccionados";
   echo  "<script>//alert('none');
           $('#set_serv').val(1);
        </script>";
?>
<script>

$('#lodinf').html('<div class="info"><b>Fecha: -</b></div><div class="txt">Seleccion&aacute; una fecha para ver los horarios de turnos disponibles.</div>');
</script>
<?
}else if($iserv!=""){
$iserv = str_replace("-",",",$iserv);
$gets = mysqli_query($conn, "SELECT serv_id, serv_nom FROM tu_emps_serv WHERE serv_id IN (".$iserv.") 
&& serv_estado=1 && serv_tipo=1 ORDER BY serv_nom");
$rows = mysqli_num_rows($gets);
if($rows == 1){ $rowstxt = "1 servicio seleccionado"; }
else if ($rows > 1){ $rowstxt = $rows." servicios seleccionados";
    echo  "<script>//alert('1');
            $('#set_serv').val(1);
        </script>";}
else{
    //$rowstxt = "0 servicios seleccionados";
    $rowstxt='No ten&eacute;s servicios seleccionados';
    echo  "<script>
            $('#set_serv').val(1);
        </script>";
}
$r = 0;
?><ul id="hnavm"><li><span><a href="javascript:void(0)"><? echo $rowstxt; ?></a></span><ul class="submenu">
<? while($dats = mysqli_fetch_assoc($gets)){
?><li><a href="javascript:void(0)" onclick="delserv('<? echo $emurl; ?>','<? echo $dats['serv_id']; ?>')"><? echo $dats['serv_nom']; ?> <img src="<? echo $rootdir; ?>frame/imagenes/delete2.png" width="10" height="10" class="img_b"></a></li><? } ?>
</ul></li>
</ul>
<? } ?>