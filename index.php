<?php
/*==========================================
  COMP1917 in a day
  by Stephen Sherratt and Dan Padilha
  
  Index page.
============================================*/
    

// Get the configuration information
require_once("config.php");

// Create an output buffer
ob_start();

// Get the list of chapters in order
require_once(CHAPTERS_DIR . CHAPTERS_LIST);
$chapter_order = explode("\n", ob_get_clean());

// Split the chapter list
foreach ($chapter_order as $chapter){
    $chapter = explode(" ", $chapter, 2);
    $chapter_list[$chapter[0]] = $chapter[1];
}

// Print the layout template
require_once(LAYOUT);
$layout = ob_get_clean();

// Check if we are reading a chapter
if (isset($_GET['link'])){

    // Check if that chapter exists
    if(array_key_exists($_GET['link'], $chapter_list)){

        ob_start();
        require_once(CHAPTERS_DIR . $_GET['link'] . "/" . SECTIONS_LIST);
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
