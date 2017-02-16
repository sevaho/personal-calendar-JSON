<?php

  //include('session.php');

  $color = $_POST['color'];
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = nl2br($_POST['description']);

  //Parsing JSON
  $opts = array('http' => array('header' => "User-Agent:MyAgent/1.0\r\n"));
  $context = stream_context_create($opts);
  $jsonString = file_get_contents('events.json', FALSE, $context);
  $data = json_decode($jsonString,true);
 
  //Adding an event to json in following format {date,title,description,color}
  function addEvent($color,$date,$title,$description){
    //Type is here a variable, if needed there can be more data arrays added in the JSON file and with the 
    //same type you are able to fill them
    $type = "events";
    global $data;
    $indexOfNewEvent = count($data[$type]);

    if (checkIfEventDateExists($type,$date) == true){
      $indexOfExistingEvent = giveArrayNumberExistingDate($type,$date);

      $data[$type][$indexOfExistingEvent][date] = $date;
      $data[$type][$indexOfExistingEvent][title] = $title;
      $data[$type][$indexOfExistingEvent][description] = $description;
      $data[$type][$indexOfExistingEvent][color] = $color;
    }
    else {
      $data[$type][$indexOfNewEvent][date] = $date;
      $data[$type][$indexOfNewEvent][title] = $title;
      $data[$type][$indexOfNewEvent][description] = $description;
      $data[$type][$indexOfNewEvent][color] = $color;
    }

    //Save the modified parsed JSON to the JSON file
    $newJsonString = json_encode($data);
    file_put_contents('events.json',$newJsonString);
  }

  function checkIfEventDateExists($type,$date){
    global $data;
    $bool = false;
    foreach ($data[$type] as $key => $entry){
      if ($entry[date] == $date){
        $bool = true;
      }
    }
    return $bool;
  }  

  function giveArrayNumberExistingDate($type,$date){
    global $data;
    $nr = 0;
    foreach ($data[$type] as $key => $entry){
      if ($entry[date] == $date){
        $nr = $key;
      }
    }
    return $nr;
  }

  //Helping with debugging
  function consolelog($data){
    if (is_array( $data))
      $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data ) . "'  );</script>";
    else
      $output = "<script>console.log( 'Debug Objects: " . $data . "'  );</script>";
    echo $output;
  }

  //Debug
  //AddEvent("orange",20170215,"title","description");

  //Add event based on php variables
  addEvent($color,$id,$title,$description);

?>

