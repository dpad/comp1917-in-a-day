<?php

class ChaptersController extends AppController {
    var $name = 'Chapters';
    var $uses = array('Chapter', 'Section');
    var $helpers = array('html');

    function index($chapter = 0){
        $this->set('chapter_title', 'COMP1917 in a Day');
        $this->set('page_title', 'COMP1917 in a Day');
        $this->set('table_of_contents', $this->Chapter->find('all', array(
                        'conditions' => 'Chapter.shown = 1'
                        )
                    ));
        $chapter = $this->Chapter->find('first', array(
                    'conditions' => 'Chapter.link = "'.$chapter.'"'
                    ));
        if ($chapter){
            $this->set('chapter_title', $chapter['Chapter']['name']);
            $this->set('chapter', $chapter);
            $this->set('page_title', 'COMP1917 in a Day >> '.$chapter['Chapter']['name']);
        } else {
            $this->redirect("/");
        }
    }
}

?>
