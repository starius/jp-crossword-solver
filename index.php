<?
$act=$_GET['act'];


if ($act=='')
{
?>
Продолжить ввод:
<br>
<form action=?act=continue method=post>
<input type=text name=str> - шифр
<br>
<input type=submit value=ВВОД>
</form>

<hr>
Новый ввод:
<br>
<form action=?act=new method=post>
<input type=text name=width> - ширина
<br>
<input type=text name=height> - высота
<br>
<input type=submit value=ВВОД>
</form>


<hr>
<br>
<a href=1.php>Разгадалка</a>


<?

}







if ($act=='new')
{
$width=(integer)($_POST['width']);
$height=(integer)($_POST['height']);


$act="editingform";
}








if ($act=='continue')
{

# формат: ШИРИНАxВЫСОТА=СТРОКА1,СТРОКА2 ...
# запись строки без разделителей, 1 - есть, 2 - нет
$str=$_POST['str'];



#########################################
# Получаем данные из POST
#########################################

$bank=explode('=',$str);

$bank1=explode('x',$bank[0]);
$width=(integer)($bank1[0]);
$height=(integer)($bank1[1]);

$proStroki=$bank[1];


$bank1=explode(',',$proStroki);
for ($y=0;$y<$height;$y++)
{
$proStroka=$bank1[$y];
for ($x=0;$x<$width;$x++)
{
$itog[$x][$y]=(integer)$proStroka[$x];
}
}

$act="editingform";
}




if ($act=='new')
{
$width=(integer)($_POST['width']);
$height=(integer)($_POST['height']);


$act="editingform";
}



if ($act=='go')
{
$width=(integer)($_GET['width']);
$height=(integer)($_GET['height']);

$itog=false;
for ($x=0; $x<$width; $x++)
{
for ($y=0; $y<$height; $y++)
{
$box="kl-{$x}-{$y}";

if (isset($_POST[$box]))
{
$itog[$x][$y]=1;
}
else
{
$itog[$x][$y]=2;
}
}
}

$act="editingform";
}











if ($act=='bezResh')
{
$width=(integer)($_GET['width']);
$height=(integer)($_GET['height']);

$itog=unserialize($_GET['itog']);

$act="editingform";
}






if ($act=="editingform")
{

$stroki=false;
for ($y=0; $y<$height; $y++)
{
$sost=0;
for ($x=0; $x<$width; $x++)
{
if ($itog[$x][$y]==1)
{
if ($sost==0)
{
$stroki[$y][]=1;
$sost=1;
}
else
{
$stroki[$y][(count($stroki[$y])-1)]++;
}
}
else
{
$sost=0;
}
}
}




$stolb=false;
for ($x=0; $x<$width; $x++)
{
$sost=0;
for ($y=0; $y<$height; $y++)
{
if ($itog[$x][$y]==1)
{
if ($sost==0)
{
$stolb[$x][]=1;
$sost=1;
}
else
{
$stolb[$x][(count($stolb[$x])-1)]++;
}
}
else
{
$sost=0;
}
}
}

?>
<html>
<body onkeydown=down(event)>
<?


echo "<form action=?act=go&width={$width}&height={$height} method=post>";


echo "<table border=1 style='border-color:black;border-Collapse:collapse;border-width:1;'>";

echo "<tr><td>&nbsp;";



for ($x=0; $x<$width; $x++)
{
echo "<td valign=bottom align=center>";
$br=0;

for ($y=0; $y<count($stolb[$x]); $y++)
{
if ($br==0)
{
$br=1;
}
else
{
echo "<br>";
}
echo $stolb[$x][$y];
}

}




for ($y=0; $y<$height; $y++)
{
echo "<tr>";
echo "<td align=right>";

for ($x=0; $x<count($stroki[$y]); $x++)
{
echo "&nbsp;";
echo $stroki[$y][$x];
}

for ($x=0; $x<$width; $x++)
{

echo "<td width=15 height=15 align=center onmousedown=myclick('ch-{$x}-{$y}','td-{$x}-{$y}') id=td-{$x}-{$y} onmouseover=mymouseover('ch-{$x}-{$y}','td-{$x}-{$y}')";
if ($itog[$x][$y]==1 && $_GET['act']!='bezResh')
{
echo " bgcolor=black";
}

echo ">";

if ($_GET['act']!='bezResh')
{
echo "<input type=checkbox name=kl-{$x}-{$y} id=ch-{$x}-{$y} style='display:none;' ";

if ($itog[$x][$y]==1)
{
echo " checked";
}
echo ">";
}

}
}

echo "</table>";


if ($_GET['act']=='bezResh')
{
exit('');
}


?>

<script type=text/javascript>
<!--
chertim=0;



function down(event)
{
if (event.keyCode==32)
{
chertim++;
if (chertim==3)
{
chertim=0;
}
}


}



function myclick(kl,td)
{
if (document.getElementById(kl).checked)
{
document.getElementById(kl).checked=false;
document.getElementById(td).style.background='white';
}
else
{
document.getElementById(kl).checked=true;
document.getElementById(td).style.background='black';
}
}




function mymouseover(kl,td)
{
if (chertim==1)
{
document.getElementById(kl).checked=true;
document.getElementById(td).style.background='black';
}
if (chertim==2)
{
document.getElementById(kl).checked=false;
document.getElementById(td).style.background='white';
}
}




-->
</script>


<?




echo "<input type=submit value=GO>";




if ($_GET['act']!='new')
{
echo "<br><br>";
echo "Код задачи: <br>{$width}x{$height}=";

$nado=0;
for ($y=0; $y<$height; $y++)
{
if ($nado==1)
{
echo ",";
}
else
{
$nado=1;
}



echo implode(" ",$stroki[$y]);

}

echo "x";

$nado=0;
for ($x=0; $x<$width; $x++)
{
if ($nado==1)
{
echo ",";
}
else
{
$nado=1;
}
echo implode(" ",$stolb[$x]);
}



echo "<br><br>";
echo "Код решения: <br>{$width}x{$height}=";
for ($y=0; $y<$height; $y++)
{
for ($x=0; $x<$width; $x++)
{
echo $itog[$x][$y];
}
echo ",";
}


echo "<br><br> /";
}

echo "<a href=?act=bezResh&itog=".serialize($itog)."&width=$width&height=$height target=_blank>Без решения</a>";




}





?>
