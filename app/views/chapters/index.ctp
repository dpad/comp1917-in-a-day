<?php
foreach($chapter['Section'] as $n => $section){
    echo "<div class='section'><p>";
    if ($section['name'] != NULL){
        echo "<h1>".$section['name']."</h1>";
    }
    echo $section['text'];
    echo "</p></div>";
}
?>
