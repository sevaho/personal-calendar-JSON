<?php
  include('session.php');

  $id = $_POST['id'];
  consolelog($id);
    $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
    $context = stream_context_create($opts);
    $jsonString = file_get_contents('events.json', FALSE, $context);
    $data = json_decode($jsonString,true);

    //consolelog($data[events][5][date]);
 
  function addEvent($type,$date,$title,$extra){
   $length = count($data[$type]);

        return;
    $data[$type][$length][date] = $date;
    $data[$type][$length][title] = $title;
    $data[$type][$length][extra] = $extra;

    $newJsonString = json_encode($data);
    file_put_contents('events.json',$newJsonString);
  }
  function checkExistingDate($type,$date){
    global $data;
    $bool = false;

    foreach ($data[$type] as $key => $entry){
      if ($entry[date] == $date)
        $bool = true;
    }
    return $bool;
  }
    function consolelog($data){
    if (is_array( $data))
      $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data ) . "'  );</script>";
    else
      $output = "<script>console.log( 'Debug Objects: " . $data . "'  );</script>";
    echo $output;
  }
  //addEvent("events",20170215,"ANOTHER","AA");
  //if (checkExistingDate("events",2017021) == true)
?>

