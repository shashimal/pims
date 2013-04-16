<?php


class StdInputResultTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('StdInputResult');
    }
}