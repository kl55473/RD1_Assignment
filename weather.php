<?php
session_start();
$ind=$_GET['ind'];
     //echo($ind);
$nowcity=$_SESSION["city"];
header("Content-type: text/html; charset=utf-8");
require ("config.php");
     
$link = mysqli_connect ( $dbhost, $dbuser, $dbpass ) or die ( mysqli_connect_error() );
$result = mysqli_query ( $link, "set names utf8" );
mysqli_select_db ( $link, $dbname );

     
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel=stylesheet type="text/css" href="style.css">
    <title>Document</title>
</head>
<body>
    
<?php
        $sql3="select c_img from city where c_name='$nowcity'";
        $result3 = mysqli_query ( $link, $sql3 )or die ("3");
        $row=mysqli_fetch_assoc($result3);
        $img=$row['c_img'];
        //echo $img;
?>
        <img src="<?=$img?>" width="12.5%"><br>
        <div id="nowcity"><?=$nowcity?></div><br>
        <div id="choose">
            <input class="button" type="button" name="nowcity" value="現在天氣" onclick="location.href='weather.php?ind=1'">
            <input class="button" type="button" name="twocity" value="未來２天天氣" onclick="location.href='weather.php?ind=2'">
            <input class="button" type="button" name="weekcity" value="未來１週天氣" onclick="location.href='weather.php?ind=3'">
            <input class="button" type="button" name="hourrain" value="過去雨量" onclick="location.href='weather.php?ind=4'">
            <input class="button" type="button" name="nowcity" value="重新選擇縣市" onclick="location.href='index.php'">
        </div>
        <br>
        <div id=text></div><br>
        <?php
     if($ind==1){
?>
    <div id='text'>現在天氣</div>
<?php
        if(isset($nowcity)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            
            // $sql2="drop table nowcity";
            // $result2 = mysqli_query ( $link, $sql2 )or die ("2");
            
            // $sql3="CREATE TABLE `nowcity` (
            //     `n_id` int(11) NOT NULL auto_increment,
            //     `n_name` varchar(20) NOT NULL,
            //     `n_wx` varchar(30) NOT NULL,
            //     `n_pop` int(11) NOT NULL,
            //     `n_minT` int(11) NOT NULL,
            //     `n_maxT` int(11) NOT NULL,
            //     `n_CI` varchar(50) NOT NULL,
            //     `n_startTime` DATETIME NOT NULL,
            //     `n_endTime` DATETIME NOT NULL,
            //       PRIMARY KEY(n_id)
            //   )";
            // $result3 = mysqli_query ( $link, $sql3 )or die ("3");

            foreach($content->records->location as $i){
                $n_name=($i->locationName);
                $n_startTime=($i->weatherElement[0]->time[0]->startTime);
                $n_endTime=($i->weatherElement[0]->time[0]->endTime);
                $n_wx=($i->weatherElement[0]->time[0]->parameter->parameterName);
                $n_pop=($i->weatherElement[1]->time[0]->parameter->parameterName);
                $n_minT=($i->weatherElement[2]->time[0]->parameter->parameterName);
                $n_maxT=($i->weatherElement[4]->time[0]->parameter->parameterName);
                $n_CI=($i->weatherElement[3]->time[0]->parameter->parameterName);
                $sql2="select n_starTtime from nowcity where n_name='$n_name'";
                $result2 = mysqli_query ( $link, $sql2 )or die ("2");
                if($n_name==$nowcity){
                        //var_dump($i);
                    ?><div id="nowc"><?php
                    echo $n_startTime."~".$n_endTime."<br>";
                    echo "天氣現象：".$n_wx."<br>";
                    echo "降雨機率：".$n_pop."(".($i->weatherElement[1]->time[0]->parameter->parameterUnit).")<br>";
                    echo "最低溫度：".$n_minT."(".($i->weatherElement[2]->time[0]->parameter->parameterUnit).")<br>";
                    echo "最高溫度：".$n_maxT."(".($i->weatherElement[4]->time[0]->parameter->parameterUnit).")<br>";
                    echo "舒適度指數：".$n_CI."<br>";
                    ?></div><?php
            
                }
                if($result2!=$n_startTime){
                    $sql="insert into nowcity(n_name,n_startTime,n_endTime,n_minT,n_maxT,n_pop,n_wx,n_CI) values('$n_name','$n_startTime','$n_endTime','$n_minT','$n_maxT','$n_pop','$n_wx','$n_CI')";
                    $result = mysqli_query ( $link, $sql )or die ("1");
                }
            }
        }
    }
    if($ind==2){
?>
    <div id='text'>未來２天天氣</div><br>
<?php
        if(isset($nowcity)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            //var_dump($content);
            // $sql2="drop table ftcity";
            // $result2 = mysqli_query ( $link, $sql2 )or die ("2");
            
            // $sql3="CREATE TABLE `ftcity` (
            //     `ft_id` int(11) NOT NULL auto_increment,
            //     `ft_name` varchar(20) NOT NULL,
            //     `ft_show` varchar(100) NOT NULL,
            //     `ft_startTime` DATETIME NOT NULL,
            //     `ft_endTime` DATETIME NOT NULL,
            //         PRIMARY KEY(ft_id)
            //   )";
            // $result3 = mysqli_query ( $link, $sql3 )or die ("3");
            $a=0;
            $b=0;
            $c=0;
            foreach($content->records->locations[0]->location as $i){//縣市
                $ft_name=$i->locationName;
                if($b==0){
                    $start3=($i->weatherElement[6]->time[23]->startTime);
                    if($a==0){
                        $sql3="select ft_starTtime from ftcity order by ft_starTtime desc limit 0,1";
                        $result3 = mysqli_query ( $link, $sql3 )or die ("2");
                        $row = mysqli_fetch_assoc($result3);
                        if(strtotime($start3)!=strtotime($row['ft_starTtime'])){
                            $a=2;
                        }
                        else{
                            $a=1;
                            $b=1;
                        }
                    }
                    if($a==2){
                        for($l=0;$l<24;$l++){
                            $ft_startTime=($i->weatherElement[6]->time[$l]->startTime);
                            $ft_endTime=($i->weatherElement[6]->time[$l]->endTime);
                            $start2=($i->weatherElement[6]->time[$l]);
                            $ft_show=$start2->elementValue[0]->value;
                            $sql2="select ft_starTtime from ftcity where ft_name='$ft_name' order by ft_starTtime desc";
                            $result2 = mysqli_query ( $link, $sql2 )or die ("2");
                            $row = mysqli_fetch_assoc($result2);
                            if(strtotime($row['ft_starTtime'])!=strtotime($ft_startTime)){
                                $sql="insert into ftcity(ft_name,ft_startTime,ft_endTime,ft_show) values('$ft_name','$ft_startTime','$ft_endTime','$ft_show')";
                                $result = mysqli_query ( $link, $sql )or die ("1");
                            }                        
                        }
                    }
                }
                if($ft_name==$nowcity){
                    //var_dump($i);
                    // $z=0;
                    // $y=0;
                    $c=1;
                    for($j=0;$j<24;$j++){
                        $start=($i->weatherElement[6]->time[$j]->startTime);
                        $end=($i->weatherElement[6]->time[$j]->endTime);
                        $start1=($i->weatherElement[6]->time[$j]);
                        ?><div id="nowc"><?php
                        echo $start."~".$end."<br>";
                        echo ($i->weatherElement[6]->description)."：".($start1->elementValue[0]->value)."<br>";
                        ?></div><?php
                        // while($z<24){
                        //     $com=($i->weatherElement[1]->time[$z]);
                        //     $com1=($i->weatherElement[2]->time[$z]);
                        //     $com2=($i->weatherElement[3]->time[$z]);
                        //     $com3=($i->weatherElement[4]->time[$z]);
                        //     $com4=($i->weatherElement[5]->time[$z]);
                        //     $com5=($i->weatherElement[8]->time[$z]);
                        //     $com6=($i->weatherElement[9]->time[$z]);
                        //     $com7=($i->weatherElement[10]->time[$z]);
                        //     if(strtotime($com->endTime) <= strtotime($end) && strtotime($com->endTime) > strtotime($start)){
                        //         echo  ($com->startTime)."~".($com->endTime)." 的".($i->weatherElement[1]->description)."：".($com->elementValue[0]->value)."<br>";
                        //         echo  ($com4->dataTime)."的".($i->weatherElement[5]->description)."：".($com4->elementValue[1]->value)."<br>";
                        //         echo  ($com1->dataTime)."的".($i->weatherElement[2]->description)."：".($com1->elementValue[0]->value)."(".($com1->elementValue[0]->measures).")<br>";
                        //         echo  ($com2->dataTime)."的".($i->weatherElement[3]->description)."：".($com2->elementValue[0]->value)."(".($com2->elementValue[0]->measures).")<br>";
                        //         echo  ($com7->dataTime)."的".($i->weatherElement[10]->description)."：".($com7->elementValue[0]->value)."(".($com7->elementValue[0]->measures).")<br>";
                        //         echo  ($com3->dataTime)."的".($i->weatherElement[4]->description)."：".($com3->elementValue[0]->value)."(".($com3->elementValue[0]->measures).")<br>";
                        //         echo  ($com5->dataTime)."的".($i->weatherElement[8]->description)."：".($com5->elementValue[0]->value)."(".($com5->elementValue[0]->measures).")<br>";
                        //         echo  ($com6->dataTime)."的".($i->weatherElement[9]->description)."：".($com6->elementValue[0]->value)."<br>";
                                
                        //         $z++;
                        //     }
                        //     else{
                        //         break;  
                        //     }
                        
                        // }              
?><hr><?php
                    }       
                }
                if($c==1){
                    break;
                }
            }
        }
    }
    if($ind==3){
?>
    <div id='text'>未來１週天氣</div><br>
<?php
        if(isset($nowcity)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            //var_dump($content);

            // $sql2="drop table fwcity";
            // $result2 = mysqli_query ( $link, $sql2 )or die ("2");
            
            // $sql3="CREATE TABLE `fwcity` (
            //     `fw_id` int(11) NOT NULL auto_increment,
            //     `fw_name` varchar(20) NOT NULL,
            //     `fw_show` varchar(100) NOT NULL,
            //     `fw_startTime` DATETIME NOT NULL,
            //     `fw_endTime` DATETIME NOT NULL,
            //         PRIMARY KEY(fw_id)
            //   )";
            // $result3 = mysqli_query ( $link, $sql3 )or die ("3");
            $a=0;
            $b=0;
            $c=0;
            foreach($content->records->locations[0]->location as $i){
                $fw_name=$i->locationName;
                if($b==0){
                    $start3=($i->weatherElement[10]->time[13]->startTime);
                    if($a==0){
                        $sql3="select fw_starTtime from fwcity order by fw_starTtime desc limit 0,1";
                        $result3 = mysqli_query ( $link, $sql3 )or die ("2");
                        $row = mysqli_fetch_assoc($result3);
                        if(strtotime($start3)!=strtotime($row['fw_starTtime'])){
                            $a=2;
                        }
                        else{
                            $a=1;
                            $b=1;
                        }
                    }
                    if($a==2){
                        for($l=0;$l<14;$l++){                  
                                //echo $fw_name;
                            $fw_startTime=($i->weatherElement[10]->time[$l]->startTime);
                            $fw_endTime=($i->weatherElement[10]->time[$l]->endTime);
                            $start2=($i->weatherElement[10]->time[$l]);
                            $fw_show=$start2->elementValue[0]->value;
                            $sql2="select fw_starTtime from fwcity where fw_name='$fw_name' order by fw_starTtime desc";
                            $result2 = mysqli_query ( $link, $sql2 )or die ("2");
                            $row = mysqli_fetch_assoc($result2);
                            if(strtotime($row['fw_starTtime'])!=strtotime($fw_startTime)){
                                $sql="insert into fwcity(fw_name,fw_startTime,fw_endTime,fw_show) values('$fw_name','$fw_startTime','$fw_endTime','$fw_show')";
                                $result = mysqli_query ( $link, $sql )or die ("1");
                            }                        
                        }
                    }
                }
                if($fw_name==$nowcity){
                    //var_dump($i);
                    // $z=0;
                    // $y=0;
                    $c=1;
                    for($j=0;$j<14;$j++){
                        $start=($i->weatherElement[0]->time[$j]->startTime);
                        $end=($i->weatherElement[0]->time[$j]->endTime);
                        $start1=($i->weatherElement[10]->time[$j]->elementValue[0]);
                        ?><div id="nowc"><?php
                        echo $start."~".$end."<br><br>";
                        echo ($i->weatherElement[10]->description)."：".($start1->value)."<br>";
                        ?></div><?php
?><hr><?php
                    }
                }
                if($c==1){
                    break;
                }
            }
        }
    }
    if($ind==4){
?>
        <div id='text'>過去雨量</div><br>
<?php
        
        if(isset($nowcity)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            //var_dump($content);
            // $sql2="drop table rain";
            // $result2 = mysqli_query ( $link, $sql2 )or die ("2");
            
            // $sql3="CREATE TABLE `rain` (
            //     `r_id` int(11) NOT NULL auto_increment,
            //     `r_name` varchar(20) NOT NULL,
            //     `r_town` varchar(20) NOT NULL,
            //     `r_city` varchar(20) NOT NULL,
            //     `hr_data` int(11) NOT NULL,
            //     `day_data` int(11) NOT NULL,
            //     `r_time` DATETIME NOT NULL,
            //         PRIMARY KEY(r_id)
            //   )";
            // $result3 = mysqli_query ( $link, $sql3 )or die ("3");
            $a=0;
            foreach($content->records->location as $i){
                $r_city=$i->parameter[0]->parameterValue;
                //echo $hr_city;
                $r_time=$i->time->obsTime;
                $r_town=$i->parameter[2]->parameterValue;
                $r_name=$i->locationName;
                $hr_data=$i->weatherElement[1]->elementValue;
                $day_data=$i->weatherElement[6]->elementValue;
                if($a==0){
                $sql2="select r_time from rain where r_name='$r_name'";
                $result2 = mysqli_query ( $link, $sql2 )or die ("2");
                $row = mysqli_fetch_assoc($result2);
                if(strtotime($r_time)==strtotime($row['r_time'])){
                    $a=1;
                    // $sql="insert into fwcity(fw_name,fw_startTime,fw_endTime,fw_show) values('$fw_name','$fw_startTime','$fw_endTime','$fw_show')";
                    // $result = mysqli_query ( $link, $sql )or die ("1");
                }
                else{
                    $a=2;
                }
                }
                if($a==2){
                    $sql="insert into rain(r_city,r_name,r_town,r_time,hr_data,day_data) values('$r_city','$r_name','$r_town','$r_time','$hr_data','$day_data')";
                    $result = mysqli_query ( $link, $sql )or die ("error insert");
                }
                if($r_city==$nowcity){
                    //var_dump($i);
                    ?>
                    <div class="col-xs-3">
                    <?php
                    echo "城鎮：".$r_town."<br>";
                    echo "地區：".$r_name."<br>";
                    echo "時間：".$r_time."<br>";
                    echo "過去1個小時雨量：".$hr_data."<br>";
                    echo "過去24個小時雨量：".$day_data."<br>";
                    ?>
                    <hr>
                    </div>
<?php
                }
            }
        }
    }
?>

<script type="text/javascript"> 
window.onload=function(){ 
setInterval(function(){ 
var date=new Date(); 
var year=date.getFullYear(); //獲取當前年份 
var mon=date.getMonth()+1; //獲取當前月份 
var da=date.getDate(); //獲取當前日 
var h=date.getHours(); //獲取小時 
var m=date.getMinutes(); //獲取分鐘 
var s=date.getSeconds(); //獲取秒 
var d=document.getElementById('text'); 
d.innerHTML=year+'/'+mon+'/'+da+' '+h+':'+m+':'+s; },1000) }
</script>
</body>
</html>