<?php
session_start();
$ind=$_GET['ind'];
     //echo($ind);
$city=$_SESSION["city"];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php
     if($ind==1){
?>
        <input type="button" name="nowcity" value="現在天氣" onclick="location.href='weather.php?ind=1'">
        <input type="button" name="twocity" value="未來兩天天氣" onclick="location.href='weather.php?ind=2'">
        <input type="button" name="weekcity" value="一週天氣" onclick="location.href='weather.php?ind=3'">
        <input type="button" name="nowcity" value="重新選擇縣市" onclick="location.href='index.php'"><br>
<?php
        if(isset($city)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            $sql2="drop table nowcity";
            $result2 = mysqli_query ( $link, $sql2 )or die ("2");
            
            $sql3="CREATE TABLE `nowcity` (
                `n_id` int(11) NOT NULL auto_increment,
                `n_name` varchar(20) NOT NULL,
                `n_wx` varchar(30) NOT NULL,
                `n_pop` int(11) NOT NULL,
                `n_minT` int(11) NOT NULL,
                `n_maxT` int(11) NOT NULL,
                `n_CI` varchar(50) NOT NULL,
                `n_startTime` TIMESTAMP NOT NULL,
                `n_endTime` TIMESTAMP NOT NULL,
                PRIMARY KEY(n_id)
            )";
            $result3 = mysqli_query ( $link, $sql3 )or die ("3");

            foreach($content->records->location as $i){
                $n_name=($i->locationName);
                $n_startTime=($i->weatherElement[0]->time[0]->startTime);
                $n_endTime=($i->weatherElement[0]->time[0]->endTime);
                $n_wx=($i->weatherElement[0]->time[0]->parameter->parameterName);
                $n_pop=($i->weatherElement[1]->time[0]->parameter->parameterName);
                $n_minT=($i->weatherElement[2]->time[0]->parameter->parameterName);
                $n_maxT=($i->weatherElement[4]->time[0]->parameter->parameterName);
                $n_CI=($i->weatherElement[3]->time[0]->parameter->parameterName);
                $sql="insert into nowcity(n_name,n_startTime,n_endTime,n_minT,n_maxT,n_pop,n_wx,n_CI) values('$n_name','$n_startTime','$n_endTime','$n_minT','$n_maxT','$n_pop','$n_wx','$n_CI')";
                $result = mysqli_query ( $link, $sql )or die ("1");
                    if($i->locationName==$city){
                        //var_dump($i);
                        echo $n_name."<br>";
                        echo $n_startTime."~".$n_endTime."<br>";
                        echo "天氣現象：".$n_wx."<br>";
                        echo "降雨機率：".$n_pop."(".($i->weatherElement[1]->time[0]->parameter->parameterUnit).")<br>";
                        echo "最低溫度：".$n_minT."(".($i->weatherElement[2]->time[0]->parameter->parameterUnit).")<br>";
                        echo "最高溫度：".$n_maxT."(".($i->weatherElement[4]->time[0]->parameter->parameterUnit).")<br>";
                        echo "舒適度指數：".$n_CI."<br>";

            
                    }
                }
            }
        }
    if($ind==2){
?> 
        <input type="button" name="nowcity" value="現在天氣" onclick="location.href='weather.php?ind=1'">
        <input type="button" name="twocity" value="未來兩天天氣" onclick="location.href='weather.php?ind=2'">
        <input type="button" name="weekcity" value="一週天氣" onclick="location.href='weather.php?ind=3'">
        <input type="button" name="nowcity" value="重新選擇縣市" onclick="location.href='index.php'"><br>
<?php
        if(isset($city)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            //var_dump($content);
            foreach($content->records->locations[0]->location as $i){
                if($i->locationName==$city){
                    //var_dump($i);
                    echo $i->locationName."<br>";
                    // $z=0;
                    // $y=0;
                    for($j=0;$j<6;$j++){
                        $start=($i->weatherElement[0]->time[$j]->startTime);
                        $end=($i->weatherElement[0]->time[$j]->endTime);
                        $start1=($i->weatherElement[6]->time[$j]);
                        echo $start."~".$end."<br>";
                        echo ($i->weatherElement[6]->description)."：".($start1->elementValue[0]->value)."<br>";
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
            }
        }
    }
    if($ind==3){
?>
        <input type="button" name="nowcity" value="現在天氣" onclick="location.href='weather.php?ind=1'">
        <input type="button" name="twocity" value="未來兩天天氣" onclick="location.href='weather.php?ind=2'">
        <input type="button" name="weekcity" value="一週天氣" onclick="location.href='weather.php?ind=3'">
        <input type="button" name="nowcity" value="重新選擇縣市" onclick="location.href='index.php'"><br>
<?php
        if(isset($city)){
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            //var_dump($content);
            foreach($content->records->locations[0]->location as $i){
                if($i->locationName==$city){
                    //var_dump($i);
                    echo $i->locationName."<br>";
                    $z=0;
                    $y=0;
                    for($j=0;$j<6;$j++){
                        $start=($i->weatherElement[0]->time[$j]->startTime);
                        $end=($i->weatherElement[0]->time[$j]->endTime);
                        echo $start."~".$end."<br>";
                        for($k=0;$k<15;$k++){
                        if($k==10)
                            continue;
                        echo ($i->weatherElement[$k]->description)."：".($i->weatherElement[$k]->time[$j]->elementValue[0]->value)."(".($i->weatherElement[$k]->time[$j]->elementValue[0]->measures).")<br>";
                        }      
?><hr><?php
                    }
                }
            }
        }
    }
?>
</body>
</html>