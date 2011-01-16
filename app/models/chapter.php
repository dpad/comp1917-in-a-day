<?php

class Chapter extends AppModel {
    var $name = 'Chapter';
    var $hasMany = array(
            'Section' => array(
                'className'     => 'Section',
                'order'         => 'Section.order ASC',
                'dependent'     => true
                )
            );
}

?>
