<?php

  //include('session.php');

  $color = $_POST['color'];
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = nl2br($_POST['description']);

  $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
  $context = stream_context_create($opts);
  $jsonString = file_get_contents('events.json', FALSE, $context);
  $data = json_decode($jsonString,true);
 
  function addEvent($color,$date,$title,$description){
    $type = "events";
    global $data;
    $nr = count($data[$type]);

    if (checkExistingDate($type,$date) == true){
      $nr = giveNumberExistingDate($type,$date);

      $data[$type][$nr][date] = $date;
      $data[$type][$nr][title] = $title;
      $data[$type][$nr][description] = $description;
      $data[$type][$nr][color] = $color;
    }
    else {
      $data[$type][$nr][date] = $date;
      $data[$type][$nr][title] = $title;
      $data[$type][$nr][description] = $description;
      $data[$type][$nr][color] = $color;
    }

    $newJsonString = json_encode($data);
    file_put_contents('events.json',$newJsonString);
  }

  function checkExistingDate($type,$date){
    global $data;
    $bool = false;

    foreach ($data[$type] as $key => $entry){
      if ($entry[date] == $date){
        $bool = true;
      }
    }
    return $bool;
  }  

  function giveNumberExistingDate($type,$date){
    global $data;
    $nr = 0;

    foreach ($data[$type] as $key => $entry){
      if ($entry[date] == $date){
        $nr = $key;
      }
    }
    return $nr;
  }

  function consolelog($data){
    if (is_array( $data))
      $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data ) . "'  );</script>";
    else
      $output = "<script>console.log( 'Debug Objects: " . $data . "'  );</script>";
    echo $output;
  }

  //addEvent("orange",20170215,"title","description");
  addEvent($color,$id,$title,$description);

?>

