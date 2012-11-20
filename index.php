<?php
/*==========================================
  COMP1917 in a day
  by Stephen Sherratt and Dan Padilha
  
  Index page.
============================================*/

include_once 'geshi/geshi.php';

function getTemplate($template){
    ob_start();
    require_once($template);
    return ob_get_clean();
}

function printSection($chapter_dir, $section, $i){
    $section = explode("\n", getTemplate($chapter_dir.$section.".section"));
    $nl2br = true;
    $last  = true;
    $in_pre = false;

    $string = "";
    $footnotes = array();

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
                $string .= $line."";
                $nl2br = false;
            } else if (preg_match("/<\/pre>$/i", $line) > 0){
                $string .= $line;
                $nl2br = $last;
            } else if (preg_match("/^\[\[((.*?)\|)?(.*)$/i", $line, $matches) > 0){
                $pre = "";
                if ($matches[1] == "|"){
                    $pre_syntax = false;
                } else {
                    $pre_syntax = $matches[2];
                }
                $pre .= $matches[3];
                $in_pre = true;
                $nl2br = false;
            } else if (preg_match("/(.*)\]\]$/i", $line, $matches) > 0 && $in_pre){
                if ($matches[1] != ""){
                    $pre .= "\n".$matches[1];
                }
                $pre = codeToHtml($pre, $pre_syntax);
                $string .= $pre;
                $in_pre = false;
                $pre = "";
                $pre_syntax = false;
                $nl2br = $last;
            } else if (preg_match("/^{{(.+?)(\|(.+))?}}(!)?$/i", $line, $matches) > 0){
                if ($matches[3] == ""){
                    $matches[3] = false;
                }
                if ($matches[4] == "!"){
                    $string .= fileToHtml($chapter_dir.$matches[1], false, $matches[3])."<br/>";
                } else {
                    $string .= fileToHtml($chapter_dir.$matches[1], true, $matches[3])."<br/>";
                }
            } else if (preg_match_all("/\[\[(.+?)\]\]/", $line, $matches) > 0){
                foreach ($matches[1] as $match){
                    array_push($footnotes, $match);
                    $footnote = count($footnotes);
                    $line = preg_replace("/\[\[(.+?)\]\]/", "<sup class='footnote'><a name='head-$i-$footnote' class='anchor'></a><a href='#foot-$i-$footnote'>$footnote</a></sup>", $line, 1);
                }
                $string .= $line;
            } else {
                if ($in_pre){
                    $pre .= "\n".$line;
                } else {
                    $string .= $line;
                }
            }

            if ($nl2br){
                $string .= "<br/>";
            } else {
                $string .= "\n";
            }

        }
    }

    if (count($footnotes) > 0){
        $string .= "<hr class='footnote'/>";
        foreach ($footnotes as $j => $footnote){
            $j += 1;
            $string .= "<div class='footnote'><a name='foot-$i-$j' class='anchor'></a><a href='#head-$i-$j' class='footanchor'>^$j</a>$footnote</div>\n";
        }
    }

    $string = preg_replace("/\*\*(.+?)\*\*/", "<strong>\\1</strong>", $string);
    $string = preg_replace("/\*\*\*(.+?)\*\*\*/", "<em>\\1</em>", $string);
    $string = preg_replace("/___(.+?)___/", "<u>\\1</u>", $string);
    $string = preg_replace("/\?\?\?(.+?)\?\?\?/", "<span style='color:red'>\\1</span>", $string);

    return $string;
}

function fileToHtml($code_file, $download = true, $syntax = false){
    ob_start();
    require_once($code_file);
    $code = explode("\n", ob_get_clean());
    array_pop($code);
    $code = implode("\n", $code);

    if ($download){
        $string = "<div class='code'><strong>Download: <a href='".FULL_DIR."$code_file'>$code_file</a></strong><br/><br/>";
    } else {
        $string = "<div class='code'>";
    }

    return $string.codeToHtml($code, $syntax, false);
}

function codeToHtml($code, $syntax = false, $self = true){
    if ($self){
        $string = "<div class='code'>";
    }

    if ($syntax === ""){
        $syntax = "bash";
    }
    $code = new GeSHi($code, $syntax);
    $code->set_overall_id(md5($code_file)."lines");
    if ($syntax && ($syntax != "bash")){
        $code->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        $code->set_overall_style('background:#fcfcfc; margin:-10px; color:#000;');
        $code->set_line_style('background:#fff; padding-left:10px; border-left:1px solid #eee;');
    } else {
        $code->enable_line_numbers(false);
        $code->set_line_style('background:#fff;');
    }
    $code->enable_keyword_links(false);
    $string .= $code->parse_code();
    $string .= "</div>";

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
        $i = 0;
        foreach ($section_list as $link => $section){
            $i++;
            if ($link != ""){
                $content .= "<a name='$link'></a>";
                $content .= "<div class='section'>";
                $content .= "<h1>$section</h1>";
                $content .= printSection(CHAPTERS_DIR.$_GET['link']."/", $link, $i);
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

    $content = "<div class='section warning small'><strong>WARNING!</strong><br/>This guide contains strong language, hipster-bashing, parody machismo, and other acts which may be perceived as inappropriate.<br/>If you can not tolerate pure awesomeness, have a weak stomach, or lack balls of steel, then don't say we didn't warn you.</div>";
    $content .= "<div class='section'>".$toc."</div>";
    $content .= "<div class='section'><p><h2>For the love of code...</h2>";
    $content .= "<pre id='heartoutnolines' class='code'>";
    $content .= fileToHtml("heart.out", false, false);
    $content .= "</pre>";
    $content .= fileToHtml("heart.c", true, "c");
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
