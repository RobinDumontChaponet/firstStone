<?php

class GroupDAO extends Transitive\Utils\ModelDAO
{
    const TABLE_NAME = 'Group';

    public static function create(&$object)
    {
        try {
            $statement = self::prepare('INSERT INTO '.self::getTableName().' (name) values (:name)');
            $statement->bindValue(':name', $object->getName());

            $statement->execute();
            $object->setId(self::getInstance()->lastInsertId());

            return $object->getId();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function update(&$object)
    {
        try {
            $statement = self::prepare('UPDATE '.self::getTableName().' SET name=:name WHERE id=:id');
            $statement->bindValue(':id', $object->getId());
            $statement->bindValue(':name', $object->getName());

            $statement->execute();

            return self::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function getAll(): array
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName());

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ))
                $objects[$rs->id] = new Group($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }

    public static function getById(int $id): ?Group
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE id=?');
            $statement->bindParam(1, $id);
            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ))
                $object = new Group($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $object;
    }

    public static function getByName(string $name): array
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE name=?');
            $statement->bindParam(1, $name);

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ))
                $objects[$rs->id] = new Group($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }

    public static function getByUser(User $user): array
    {
        $objects = array();

        try {
            //'SELECT id, `name`, `orderByName`, `title`, `website`, `websiteTitle` FROM Artist INNER JOIN authorOf ON Artist.id=artistId WHERE baseId=?'
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' INNER JOIN `inGroup` ON id=groupId WHERE userId=:userId');
            $statement->bindValue(':userId', $user->getId());

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ))
                $objects[$rs->id] = new Group($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }
}
