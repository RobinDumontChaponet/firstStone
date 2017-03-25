<?php

abstract class DatedDAO extends \Transitive\Utils\ModelDAO
{
    const TABLE_NAME = 'Dated';

    public static function create(&$object)
    {
        $object->setCreationTime(time());
    }

    public static function update(&$object)
    {
        $object->setModificationTime(time());
    }
}
