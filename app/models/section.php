<?php

class Section extends AppModel {
    var $name = 'Section';
    var $belongsTo = array(
            'Chapter' => array(
                'className'     => 'Chapter',
                'foreignKey'    => 'chapter_id'
                )
            );
}

?>
