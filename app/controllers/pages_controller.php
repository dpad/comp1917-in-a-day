<?php

class PagesController extends AppController {
    var $name = 'Pages';
    var $uses = array('Chapter', 'Section');

    function index(){
        $this->set('chapter_title', 'COMP1917 in a Day');
        $this->set('page_title', 'COMP1917 in a Day');
        $this->set('table_of_contents', $this->Chapter->find('all', array(
                        'conditions' => 'Chapter.shown = 1'
                        )
                    ));
    }
}

?>
