<?php
/*==========================================
  COMP1917 in a day
  by Stephen Sherratt and Dan Padilha
  
  Index page.
============================================*/
    

// Get the configuration information
require_once("config.php");

// Get a list of all the chapters
$chapter_list = scandir(CHAPTERS_DIR);
array_shift($chapter_list); // Get rid of "."
array_shift($chapter_list); // Get rid of ".."

// Create an output buffer
ob_start();

// Get the list of chapters in order
require_once(CHAPTERS_DIR . CHAPTERS_LIST);
$chapter_order = explode("\n", ob_get_clean());
var_dump($chapter_order);

// Print the layout template
require_once(LAYOUT);
$layout = ob_get_clean();

echo "Yes: ".$_GET['link'];
// Check if we are reading a chapter
if (isset($_GET['link'])){

    // Check if that chapter exists
    $chapter_key = array_search($_GET['link'], $chapter_list);
    if ($chapter_key != FALSE){

        require_once(CHAPTERS_DIR . $_GET['link'] . SECTIONS_LIST);
        $chapter_contents = explode("\n", ob_get_clean());

        var_dump($chapter_contents);

    } else {
        printf("<h1>Invalid Chapter!</h1>");
    }

} else {

}

// Print out anything waiting.
ob_end_flush();

?>
