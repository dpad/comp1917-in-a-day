<?php

class ChaptersController extends AppController {
    var $name = 'Chapters';
    var $uses = array('Chapter', 'Section');
    var $helpers = array('html');

    function index($chapter){
        $this->Chapter->id = $chapter;
        if ($this->Chapter->find()){
            echo "yay!";
        } else {
            echo "WTF";
        }
    }
}

?>
