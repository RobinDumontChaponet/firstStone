<?php

class TagDAO extends Transitive\Utils\ModelDAO
{
    const TABLE_NAME = 'Tag';

    public static function create(&$object)
    {
        try {
            $statement = self::prepare('INSERT INTO '.self::getTableName().' (id, name) values (:id, :name)');
            $statement->bindValue(':id', $object->getId());
            $statement->bindValue(':name', $object->getName());

            $statement->execute();

            return self::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function update(&$object)
    {
        try {
            $statement = self::prepare('UPDATE '.self::getTableName().' SET name=:name WHERE id=:id');
            $statement->bindValue(':name', $object->getName());
            $statement->bindValue(':id', $object->getId());

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
                $objects[$rs->id] = new Tag($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }

    public static function getById(string $id): ?Region
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE id=?');
            $statement->bindParam(1, $id);
            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ))
                $object = new Tag($rs->id, $rs->name);
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
                $objects[$rs->id] = new Tag($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }

    public static function getByArticle(Article $article): array
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().', `hasTag` WHERE articleId=:id AND id=tagId');
            $statement->bindValue(':id', $article->getId());
            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ))
                $objects[$rs->id] = new Tag($rs->id, $rs->name);
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }
}
