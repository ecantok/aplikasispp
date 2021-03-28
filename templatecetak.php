<?php

  require_once 'app.php';

  if ($selectedUrl == "navbar.php") {
    //Don't come here
    // header("location:index.php");
  }
  
  class TemplateCetak extends App
  {
    public static function header()
    {
      global $conn;
      
    }
  
    public static function footer()
    {
      global $conn;
      global $app;
    }
    
  }
  TemplateCetak::header();
  

?>