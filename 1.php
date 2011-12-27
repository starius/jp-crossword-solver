<?

# ШИРИНАxВЫСОТА=СТРОКА1,СТРОКА2 ...xСТОЛБЕЦ1,СТОЛБЕЦ2...
# столбец или строка записывается так: 1 2 3 4 
# (строка слева направо, столбец сверху вниз)


$act=$_GET['act'];


if ($act=='')
{
?>
<form action=?act=go method=post>
<input type=text name=str> - шифр
<br>
<input type=submit value=ВВОД>
</form>
<?
exit('');
}


###########################
# Получаем данные из Post
###########################

$str=trim($_POST['str']);

$bank=explode('=',$str);

$bank1=explode('x',$bank[0]);
$width=(integer)($bank1[0]);
$height=(integer)($bank1[1]);


$bank1=explode('x',$bank[1]);
$proStroki=$bank1[0];
$proStolb=$bank1[1];

$bank1=explode(',',$proStroki);
for ($y=0;$y<$height;$y++)
{
$proStroka=$bank1[$y];
$bank2=explode(' ',$proStroka);
for ($x=0;$x<count($bank2);$x++)
{
$stroki[$y][$x]=$bank2[$x];
}
}

$bank1=explode(',',$proStolb);
for ($x=0;$x<$width;$x++)
{
$proStolb=$bank1[$x];
$bank2=explode(' ',$proStolb);
for ($y=0;$y<count($bank2);$y++)
{
$stolb[$x][$y]=$bank2[$y];
}
}








################################################################
# Просчитываем все возможные варианты по строкам и по столбцам
################################################################

$variantStroki=false;
$chisloKletok=$width;
for ($y=0; $y<$height; $y++)
{
$elem=$stroki[$y];
$zapas=$chisloKletok-sum($elem)-count($elem)+1;
$currentVariant=false;
getObolo(false,0,0,$zapas);
$variantStroki[$y]=$currentVariant;
}



$variantStolb=false;
$chisloKletok=$height;
for ($x=0; $x<$width; $x++)
{
$elem=$stolb[$x];
$zapas=$chisloKletok-sum($elem)-count($elem)+1;
$currentVariant=false;
getObolo(false,0,0,$zapas);
$variantStolb[$x]=$currentVariant;
}

# print_r($variantStolb);



/*
0 - изначальное
1 - точно есть
2 - точно нет
3 - разное
*/

$itog=false;

################################################################
# Исключаем негодные варианты и дополняем результат до тех пор, 
# пока хоть что-то изменяется
################################################################

$started=time();

$konec=1;
while ($konec && time()<=($started+5))
{
$konec=prohod();
}


otobraz();


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

echo "<br><br>";

/*
print_r($variantStroki);

print_r($variantStolb);
*/



# осуществляется один проход по строкам и один - по столбцам
function prohod()
{
global $variantStroki;
global $variantStolb;
global $itog;
global $height;
global $width;

$progress=0;


# проход по строкам
for ($y=0; $y<$height; $y++)
{
 for ($x=0; $x<$width; $x++)
 {
  $itog1=0;
  for($n=0; $n<count($variantStroki[$y]); $n++)
  {
   if ($variantStroki[$y][$n])
   {

    if ($itog[$x][$y])
    {
     if (($itog[$x][$y]==1 && $variantStroki[$y][$n][$x]!=1) ||
	 ($itog[$x][$y]==2 && $variantStroki[$y][$n][$x]==1)   )
     {
     $variantStroki[$y][$n]=false;
     $progress++;
     }
    }
    else
    {

     if ($variantStroki[$y][$n][$x]==1)
     {

      if ($itog1==2)
      {
      $itog1=3;
      }
      if ($itog1==0)
      {
      $itog1=1;
      }

     }
     else
     {

      if ($itog1==1)
      {
      $itog1=3;
      }
      if ($itog1==0)
      {
      $itog1=2;
      }

     }
    }

   }
  }


  if (!$itog[$x][$y])
  {
   if ($itog1==1 || $itog1==2)
   {
   $itog[$x][$y]=$itog1;
   $progress++;
   }

  }
 }
}









# проход по столбцам
for ($x=0; $x<$width; $x++)
{
for ($y=0; $y<$height; $y++)
{
$itog1=0;
for($n=0; $n<count($variantStolb[$x]); $n++)
{
if ($variantStolb[$x][$n])
{

if ($itog[$x][$y])
{
if (($itog[$x][$y]==1 && $variantStolb[$x][$n][$y]!=1) ||
    ($itog[$x][$y]==2 && $variantStolb[$x][$n][$y]==1))
{
$variantStolb[$x][$n]=false;
$progress++;
}
}
else
{

if ($variantStolb[$x][$n][$y]==1)
{

if ($itog1==2)
{
$itog1=3;
}
if ($itog1==0)
{
$itog1=1;
}

}
else
{

if ($itog1==1)
{
$itog1=3;
}
if ($itog1==0)
{
$itog1=2;
}

}

}
}

}

if (!$itog[$x][$y])
{
if ($itog1==1 || $itog1==2)
{
$itog[$x][$y]=$itog1;
$progress++;
}
}


}
}


return $progress;
}









# отображает результат
function otobraz()
{
global $height;
global $width;
global $itog;
global $stolb;
global $stroki;

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

echo "<td align=center width=15 height=15";
if ($itog[$x][$y]==1)
{
echo " bgcolor=black";
}
echo ">";

if (!$itog[$x][$y])
{
echo "?";
}

}
}
echo "</table>";
}




# функция нужна для получения всех возможных вариантов столбца или строки
function getObolo($rasstanovka,$startGruppa,$startKletka,$zapas)
{
global $elem;
global $chisloKletok;
global $currentVariant;


$thisDlina=$elem[$startGruppa];
$startGruppa++;

for ($propusk=0; $propusk<=$zapas; $propusk++)
{
$rasstanovka1=$rasstanovka;




$a=$startKletka+$propusk;



for($b=$a; $b<($thisDlina+$a);$b++)
{
$rasstanovka1[$b]=1;
}

$zapas1=$zapas;
$zapas1-=$propusk;


if ($startGruppa==count($elem))
{
$currentVariant[]=$rasstanovka1;
}
else
{
$startKletka1=$startKletka+$thisDlina+$propusk+1;
getObolo($rasstanovka1,$startGruppa,$startKletka1,$zapas1);
}

}

}








# функция считает сумму числового массива
function sum($arr)
{
$r=0;
foreach($arr as $a)
{
$r+=$a;
}
return $r;
}


?>
