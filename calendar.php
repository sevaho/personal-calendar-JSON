<?php
  //include('session.php');

  $type = $_POST['type'];
  $id = $_POST['id'];
  $title = $_POST['title'];
  $extra = nl2br($_POST['extra']);

  $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
  $context = stream_context_create($opts);
  $jsonString = file_get_contents('events.json', FALSE, $context);
  $data = json_decode($jsonString,true);
 
  function addEvent($type,$date,$title,$extra){
    global $data;
    $length = count($data[$type]);

    if (checkExistingDate($type,$date) == true){
      $nr = giveNumberExistingDate($type,$date);
      consolelog($nr);
      $data[$type][$nr][date] = $date;
      $data[$type][$nr][title] = $title;
      $data[$type][$nr][extra] = $extra;
    }
    else {
      $data[$type][$length][date] = $date;
      $data[$type][$length][title] = $title;
      $data[$type][$length][extra] = $extra;
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

  //addEvent("alerts",20170214,"title","extra");
  addEvent($type,$id,$title,$extra);

  //if (checkExistingDate("events",2017021) == true)
?>

