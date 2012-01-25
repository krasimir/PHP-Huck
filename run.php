<?php
require 'Huck/Huck.php';

define('EOL', "\n");

echo " _    _            _    \n";
echo "| |  | |          | |   \n";
echo "| |__| |_   _  ___| | __\n";
echo "|  __  | | | |/ __| |/ /\n";
echo "| |  | | |_| | (__|   < \n";
echo "|_|  |_|\__,_|\___|_|\_\\\n";

// echo "Starting up ...\n\n";
echo "\n\n";

# Output colorized text to terminal run
function puts($text, $color="NORMAL", $use_line_break = true){
    # first define colors to use
    $_colors = array(
            LIGHT_RED      => "[1;31m",
            LIGHT_GREEN     => "[1;32m",
            YELLOW         => "[1;33m",
            LIGHT_BLUE     => "[1;34m",
            MAGENTA     => "[1;35m",
            LIGHT_CYAN     => "[1;36m",
            WHITE         => "[1;37m",
            NORMAL         => "[0m",
            BLACK         => "[0;30m",
            RED         => "[0;31m",
            GREEN         => "[0;32m",
            BROWN         => "[0;33m",
            BLUE         => "[0;34m",
            CYAN         => "[0;36m",
            BOLD         => "[1m",
            UNDERSCORE     => "[4m",
            REVERSE     => "[7m",

    );
    
    $out = $_colors[$color];
    if($out == ""){ $out = "[0m"; }
    
    echo chr(27)."$out$text".chr(27).chr(27)."[0m"; #, EOL;
    if( $use_line_break )
      echo EOL;
}

$path = realpath(dirname(__FILE__));
foreach( glob($path . '/specs/*_spec.php') as $file ) {
  require_once $file;
}

$results = Huck::run();

foreach( $results as $description => $test ) {
  puts("$description", $test['fails'] ? RED : GREEN);
  
  $num_tests = count($test['results']);
  $num_failures = $test['fails'];
  $num_success = $num_tests - $num_failures;
  
  if( $num_failures > 0 )
    puts(sprintf('%d of %d %s failed in %s', $num_failures, $num_tests, $num_failures === 1 ? 'test' : 'tests', $test['time']));
  else
    puts(sprintf('%d %s passed in %s', $num_success, $num_tests === 1 ? 'test' : 'tests', $test['time']));
    
  if( $num_failures ) {
    foreach( $test['results'] as $result ) {
      if( $result->success )
        continue;
      
      echo EOL;
      puts("\tFAILED: ", RED, false);
      puts($result->description);
      
      $message = strip_tags($result->error_message);
      $message = str_replace(array("\n", '  '), array('', ' '), $message);
      
      puts("\t- {$message}");
    }
  }
  
  // puts(count($test['results']) . " test completed in {$test['time']}");
  echo "\n\n";
}