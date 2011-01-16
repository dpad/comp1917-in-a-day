<?php
/*==========================================
  COMP1917 in a day
  by Stephen Sherratt and Dan Padilha
  
  Index page.
============================================*/

function getTemplate($template){
    ob_start();
    require_once($template);
    return ob_get_clean();
}

function printSection($chapter_dir, $section){
    $section = explode("\n", getTemplate($chapter_dir.$section.".section"));
    $nl2br = true;

    $string = "";

    foreach ($section as $line){
        if (preg_match("/^{{NL2BR}}$/i", $line) > 0){
            $nl2br = true;
        } else if (preg_match("/^{{NONL2BR}}$/i", $line) > 0){
            $nl2br = false;
        } else {
            if (preg_match("/^\<pre/i", $line) > 0){
                $last = $nl2br;
                $nl2br = false;
            } else if (preg_match("/^{{(.+)}}$/i", $line, $matches) > 0){
                $line = codeToHtml($chapter_dir.$matches[1]);
            }
            $string .= $line;
            if ($nl2br){
                $string .= "<br/>";
            } else {
                $string .= "\n";
            }
            if (preg_match("/^<\/pre>$/i", $line) > 0){
                $nl2br = $last;
            }
        }
    }

    return $string;
}

function codeToHtml($code_file){
    ob_start();
    require_once($code_file);
    $code = explode("\n", ob_get_clean());

    $string = "<div class='code'><strong>Download: <a href='".FULL_DIR."$code_file'>$code_file</a></strong>";
    $string .= "<br/><a href=\"javascript:toggleLineNos('".md5($code_file)."');\">Toggle line numbers</a><pre id='".md5($code_file)."lines'>";

    $digits = 0;
    $lines = sizeof($code);
    while($lines >= 1){
        $lines = $lines / 10;
        $digits++;
    }

    foreach ($code as $i => $line){
        if (!($i == sizeof($code)-1 && $line == "")){
            $i = str_pad($i+1, $digits, "0", STR_PAD_LEFT);
            $string .= $i.": ".htmlspecialchars($line)."\n";
        }
    }

    // Annoying redundancy but saves us from having to embed jquery
    $string .= "</pre><pre style='display:none;' id='".md5($code_file)."nolines'>";
    foreach ($code as $i => $line){
        if (!($i == sizeof($code)-1 && $line == "")){
            $i = str_pad($i+1, $digits, "0", STR_PAD_LEFT);
            $string .= htmlspecialchars($line)."\n";
        }
    }

    $string .= "</pre></div>";

    return $string;

}

// Get the configuration information
require_once("config.php");

// Get the list of chapters in order
ob_start();
require_once(CHAPTERS_DIR . CHAPTERS_LIST);
$chapter_order = explode("\n", ob_get_clean());

// Split the chapter list
foreach ($chapter_order as $chapter){
    $chapter = explode(" ", $chapter, 2);
    $chapter_list[$chapter[0]] = $chapter[1];
}

// Get the layout template
$layout = getTemplate(LAYOUT);

// Build the table of contents
$toc = "";
foreach($chapter_list as $link => $name){
    $toc .= "<a href='/chapter/$link'>$name</a><br/>";
}
$layout = str_replace("{{TABLE_OF_CONTENTS}}", $toc, $layout);

// Check if we are reading a chapter
if (isset($_GET['link'])){

    // Check if that chapter exists
    if(array_key_exists($_GET['link'], $chapter_list)){

        // Grab the list of sections in the chapter
        ob_start();
        require_once(CHAPTERS_DIR . $_GET['link'] . "/" . SECTIONS_LIST);
        $chapter_contents = explode("\n", ob_get_clean());

        // Split the section list
        foreach($chapter_contents as $section){
            $section = explode(" ", $section, 2);
            $section_list[$section[0]] = $section[1];
        }

        // Add each section to the content
        $content = "";
        foreach ($section_list as $link => $section){
            if ($link != ""){
                $content .= "<div class='section'>";
                $content .= "<h1>$section</h1>";
                $content .= printSection(CHAPTERS_DIR.$_GET['link']."/", $link);
                $content .= "</div>";
            }
        }

        // Build the layout
        $layout = str_replace("{{CONTENT}}", $content, $layout);
        $layout = str_replace("{{TITLE}}", "COMP1917 in a Day >> ".$chapter_list[$_GET['link']], $layout);
        $layout = str_replace("{{HEADER}}", $chapter_list[$_GET['link']], $layout);

    } else {
        printf("<h1>Invalid Chapter!</h1>");
    }

} else {

    $content = "<div class='section'>".$toc."</div>";
    $content .= "<div class='section'><p><h2>For the love of code...</h2>";
    $content .= "<pre id='heartoutnolines' class='code'>
       #####       #####      
    ########### ###########   
   #########################  
  ########################### 
  ########################### 
  ########################### 
  ########################### 
   #########################  
   #########################  
     #####################    
      ###################     
       #################      
         #############        
            #######           
              ###             
</pre>";
    $content .= codeToHtml("heart.c");
    $content .= "</div>";

    // Build the layout
    $layout = str_replace("{{CONTENT}}", $content, $layout);
    $layout = str_replace("{{TITLE}}", "COMP1917 in a Day", $layout);
    $layout = str_replace("{{HEADER}}", "COMP1917 in a Day", $layout);
}


// Erase anything waiting
ob_end_clean();

// Print out everything
echo $layout;

?>
