<?php

class EIStream {

  static function get($exec_id) {
    $p = EIStream::path($exec_id);
    if(!EIStream::isStream($exec_id)){
      return "<ei_stream state='nostream' />";
    } 
    
    if(EIStream::finish($exec_id))
      $state = "finish";
    else
      $state = "running";
    
    exec("ls ".$p."*.ei", $files);
    sort($files, SORT_NATURAL );

    $output_f = "";
    $empty = true;

    foreach ($files as $f){
      $aux = file_get_contents($f);
      if($aux === FALSE)
	continue;
      else{
	$empty = false;

	$output_f .= $aux;
	$output_f .= "\n";

      }
      unlink($f);
      unset($aux);
    }
    $output ="";
    if($empty)
      $state .= " nonewfiles";
    else{
      $output .= "<content>\n";
      $output .= htmlspecialchars ($output_f);
      $output .= "</content>\n";
    }
    return "<ei_stream state='".$state."' >\n".$output."</ei_stream>";
  }

  static function kill( $exec_id ) {
    if(!EIStream::isStream($exec_id) || EIStream::finish($exec_id))
      return "<ei_stream state='finish' />";

    $aux = EIStream::getPID($exec_id);
    if($aux === FALSE)
      return "<ei_stream state='unknown' />";
    exec("pkill -TERM -p ".$aux);
    exec("touch ".EIStream::path($id)."terminated");
    return "<ei_stream state='stopped' />";
  }

  static function getPID( $id ) {
    return file_get_contents(EIStream::path($id)."pid");
  }

  static function isStream($id){
    return file_exists(EIStream::path($id)."pid");
  }

  static function finish($id){
    return file_exists(EIStream::path($id)."terminated");
  }

  static function path($id){
    $aux = sys_get_temp_dir()."/easyinterface_".$id;
    $dir = str_replace("\\", "/", $aux);
    unset($aux);
    return $dir."/_ei_stream/";
  }


  
}

?>