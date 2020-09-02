<?php
session_start();
$_SESSION["city"]=$_POST["city"];
//echo $_SESSION["city"];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <link rel=stylesheet type="text/css" href="style.css">
    <title>Document</title>
</head>
<body >
<?php if(!isset($_POST['city'])){ ?>
<div id="nowcity">台灣天氣</div>
<div id='list'>
<form name="form1" method="POST" action="index.php">        
<select id="city" name="city" size="1">
        <option>請選擇你的縣市</option>
        <?php
            $handle = fopen("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-57C07FAB-F956-4AF9-8639-492D280DA675","rb");
            $content = "";
            while (!feof($handle)) {
                $content .= fread($handle, 10000);
            }
            fclose($handle);
            $content = json_decode($content,false);
            foreach($content->records->location as $locate)
            {
                $c_name=$locate->locationName;
                
        ?>
        <option value="<?=$c_name?>"><?=$c_name;}?></option>
    </select>
    <input class="button1" type="submit" name="nowcity" value="確認">
    </div>
    <?php }else{
        $sql3="select c_img from city where c_name='$nowcity'";
        $result3 = mysqli_query ( $link, $sql3 )or die ("3");
        $row=mysqli_fetch_assoc($result3);
        $img=$row['c_img'];
    ?>
        <img src="<?=$img?>" width="12.5%"><br>
        <div id="nowcity"><?=$nowcity?></div><br>
        <div id="choose">
        <input class="button" type="button" name="nowcity" value="現在天氣" onclick="location.href='weather.php?ind=1'">
        <input class="button" type="button" name="twocity" value="未來２天天氣" onclick="location.href='weather.php?ind=2'">
        <input class="button" type="button" name="weekcity" value="未來１週天氣" onclick="location.href='weather.php?ind=3'">
        <input class="button" type="button" name="hourrain" value="過去雨量" onclick="location.href='weather.php?ind=4'">
        <input class="button" type="button" name="nowcity" value="重新選擇縣市" onclick="location.href='index.php'"><br>
        </div>
    <?php }?>

</body>
</html>