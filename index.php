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
    $last  = true;

    $string = "";

    foreach ($section as $line){
        if (preg_match("/^{{NL2BR}}$/i", $line) > 0){
            $nl2br = true;
            $last  = true;
        } else if (preg_match("/^{{NONL2BR}}$/i", $line) > 0){
            $nl2br = false;
            $last  = false;
        } else if (preg_match("/^{{img\/(.+?)(\|(.+?))?(\|(.+?))?}}$/i", $line, $matches) > 0){
            $string .= "<img src='/img/".$matches[1]."'";
            if (count($matches) > 4){
                $string .= " width='".$matches[5]."px'";
            }
            $string .= "><sub class='imgsub'>".$matches[3]."</sub>";
        } else {
            if (preg_match("/\<pre/i", $line) > 0){
                $string .= $line."\n";
                $nl2br = false;
            } else if (preg_match("/<\/pre>$/i", $line) > 0){
                $string .= $line;
                $nl2br = $last;
            } else if (preg_match("/^\[\[(.*)$/i", $line, $matches) > 0){
                $string .= "<pre class='code'>\n".$matches[1];
                $nl2br = false;
            } else if (preg_match("/(.*)\]\]$/i", $line, $matches) > 0){
                $string .= $matches[1]."</pre>\n";
                $nl2br = $last;
            } else if (preg_match("/^{{(.+)}}(!)?$/i", $line, $matches) > 0){
                if ($matches[2] == "!"){
                    $string .= codeToHtml($chapter_dir.$matches[1], false)."<br/>";
                } else {
                    $string .= codeToHtml($chapter_dir.$matches[1])."<br/>";
                }
            } else if (preg_match("/\[\[(.+?)\]\]/", $line, $matches) > 0){
                $string .= preg_replace("/\[\[(.+?)\]\]/", "<div class='footnote'>\\1</div>", $line);
            } else {
                $string .= $line;
            }

            if ($nl2br){
                $string .= "<br/>";
            } else {
                $string .= "\n";
            }

        }
    }

    return $string;
}

function codeToHtml($code_file, $download = true){
    ob_start();
    require_once($code_file);
    $code = explode("\n", ob_get_clean());

    if ($download){
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
    } else {
        $string .= "<pre class='code'>";
    }

    // Annoying redundancy but saves us from having to embed jquery
    if ($download){ $string .= "</pre><pre style='display:none;' id='".md5($code_file)."nolines'>"; }
    foreach ($code as $i => $line){
        if (!($i == sizeof($code)-1 && $line == "")){
            $i = str_pad($i+1, $digits, "0", STR_PAD_LEFT);
            $string .= htmlspecialchars($line)."\n";
        }
    }

    $string .= "</pre>";
    if ($download){
        $string .= "</div>";
    }

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
foreach($chapter_list as $link => $name){
    $toc .= "<a href='/chapter/$link'>$name</a><br/>";
}
$layout = str_replace("{{TABLE_OF_CONTENTS}}", $toc, $layout);


// Check if we are reading a chapter
if (isset($_GET['link'])){

    // Check if that chapter exists
    if(array_key_exists($_GET['link'], $chapter_list) || $_GET['skip']){

        $chapters = array_keys($chapter_list);
        $number = array_search($_GET['link'], $chapters);
        if ($number > 0){
            $layout = str_replace("{{PREVIOUS_CHAPTER}}", "<a id='prevchap' href='/chapter/".$chapters[$number-1]."'>&laquo; ".$chapter_list[$chapters[$number-1]]."</a>", $layout);
        } else {
            $layout = str_replace("{{PREVIOUS_CHAPTER}}", "<a id='prevchap' style='visibility:hidden;'>&nbsp;</a>", $layout);
        }
        if ($number < sizeof($chapters)-2){
            $layout = str_replace("{{NEXT_CHAPTER}}", "<a id='nextchap' href='/chapter/".$chapters[$number+1]."'>".$chapter_list[$chapters[$number+1]]." &raquo;</a>", $layout);
        } else {
            $layout = str_replace("{{NEXT_CHAPTER}}", "<a id='nextchap' style='visibility:hidden;'>&nbsp;</a>", $layout);
        }

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
                $content .= "<a name='$link'></a>";
                $content .= "<div class='section'>";
                $content .= "<h1>$section</h1>";
                $content .= printSection(CHAPTERS_DIR.$_GET['link']."/", $link);
                $content .= "</div>";
            }
        }

        // Build the layout
        if (!isset($_GET['skip'])){
            $layout = str_replace("{{CONTENT}}", $content, $layout);
            $layout = str_replace("{{TITLE}}", "COMP1917 in a Day >> ".$chapter_list[$_GET['link']], $layout);
            $layout = str_replace("{{HEADER}}", $chapter_list[$_GET['link']], $layout);
        } else {
            $layout = str_replace("{{CONTENT}}", $content, $layout);
            $layout = str_replace("{{TITLE}}", "COMP1917 in a Day >> ".$_GET['link'], $layout);
            $layout = str_replace("{{HEADER}}", $_GET['link'], $layout);
        }

    } else {

        // Build the layout
        $layout = str_replace("{{CONTENT}}", "No such chapter exists!", $layout);
        $layout = str_replace("{{TITLE}}", "COMP1917 in a Day >> 404", $layout);
        $layout = str_replace("{{HEADER}}", "404 Error!", $layout);
        $layout = str_replace("{{PREV_CHAPTER}}", "", $layout);
        $layout = str_replace("{{NEXT_CHAPTER}}", "", $layout);
    }

} else {

    $content = "<div class='section warning small'><strong>WARNING!</strong><br/>This guide contains strong language, hipster-bashing, blatant sexism, and other acts which may be perceived as inappropriate.<br/>If you can not tolerate pure awesomeness, have a weak stomach, or lack balls of steel, then don't say we didn't warn you.</div>";
    $content .= "<div class='section'>".$toc."</div>";
    $content .= "<div class='section'><p><h2>For the love of code...</h2>";
    $content .= "<pre id='heartoutnolines' class='code'>";
    $content .= codeToHtml("heart.out", false);
    $content .= "</pre>";
    $content .= codeToHtml("heart.c");
    $content .= "</div>";

    // Build the layout
    $layout = str_replace("{{CONTENT}}", $content, $layout);
    $layout = str_replace("{{TITLE}}", "COMP1917 in a Day", $layout);
    $layout = str_replace("{{HEADER}}", "COMP1917 in a Day", $layout);
    $layout = str_replace("{{PREVIOUS_CHAPTER}}", "", $layout);
    $layout = str_replace("{{NEXT_CHAPTER}}", "", $layout);
}


// Erase anything waiting
ob_end_clean();

// Print out everything
echo $layout;

?>
