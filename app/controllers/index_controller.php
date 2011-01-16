<?php

class IndexController extends AppController {
    var $name = 'Index';

    function index(){
        $this->set('chapter_title', 'COMP1917 in a Day');
        $this->set('page_title', 'COMP1917 in a Day');
        $this->set('table_of_contents', $this->Chapter->find('all'));
    }
}

?>
