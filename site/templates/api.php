<?php
header('Content-type: application/json; charset=utf-8');

//this is the magic command which grabs all data from pages. If You wanna grab specific page articles, look at the base example mentioned above
$data = $pages->index()->not('home', 'error', 'api', 'episodes');

$json = array();

$json['data']  = array();
$json['params']  = array();

$i = 0;

foreach($data as $article) {

  $images = array();

  foreach($article->images() as $image) {

    //this is Bastians snippet about gathering image data
    $images[] = array(
      'url'    => $image->url(),
      'width'  => $image->width(),
      'height' => $image->height(),
      'thumb' => thumb($image, array('width' => 320, 'height' => 320, 'crop' => true))->url()
    );
  }

  $audio = array();

  foreach($article->audio() as $track) {

    //this is Bastians snippet about gathering audio data
    $audio[] = array(
      'url'    => $track->url()
    );
  }

  $followups = array();

  foreach($article->followup()->yaml() as $followup) {

    //this is Bastians snippet about gathering structure field data
    $followups[] = array(
      'title'    => $followup["title"],
      'url'    => $followup["url"],
    );
  }

  $json['data'][] = array(
    'title' => (string)$article->title(),
    'date' => (string)$article->date('d/m/Y'),
    'track'  => (string)$article->track(),
    'interpret'  => (string)$article->interpret(),
    'text'  => (string)$article->text(),
    'images'  => $images,
    'audio'  => $audio,
    'followups'  => $followups
  );

  // this cleans up JSONS code so there is no empty entries inside objects
  $cleanData = array_filter($json['data'][$i]);
  $json['data'][$i] = $cleanData;
  $i++;

$foo = $json['params'];

  //just example of boolean. in blueprints its type: checkboxes
  if ((string)$article->adress()) { 
      $foo['adress'] = true;
  }

//example of string as param. in blueprints its type: number
if ((string)$article->time()) {
      $foo['time'] = (string)$article->time();
  }

  $json['params'] = (object)$foo;
}

echo json_encode($json);
?>