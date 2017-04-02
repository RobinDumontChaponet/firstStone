<?php

class ArticleDAO extends DatedDAO
{
    const TABLE_NAME = 'Article';

    public static function create(&$object)
    {
        parent::create($object);

        try {
            $statement = self::prepare('INSERT INTO '.self::getTableName().' (name, title, content, summary, userId, cTime, mTime, aTime) values (:name, :title, :content, :summary, :userId, :cTime, :mTime, :aTime)');
            $statement->bindValue(':name', $object->getName());
            $statement->bindValue(':title', $object->getTitle());
            $statement->bindValue(':content', $object->getContent());
            $statement->bindValue(':summary', $object->getSummary());

            $statement->bindValue(':userId', $object->getUser()->getId());
            $statement->bindValue(':cTime', $object->getCreationTime());
            $statement->bindValue(':mTime', $object->getModificationTime());
            $statement->bindValue(':aTime', $object->getAccessTime());

            $statement->execute();

//             self::commit();
            $object->setId(self::lastInsertId());

            return $object->getId();
        } catch (PDOException $e) {
//             self::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public static function update(&$object)
    {
        parent::update($object);

        try {
            $statement = self::prepare('UPDATE '.self::getTableName().' SET name=:name, title=:title, content=:content, summary=:summary, userId=:userId, cTime=:cTime, mTime=:mTime, aTime=:aTime WHERE id=:id');
            $statement->bindValue(':name', $object->getName());
            $statement->bindValue(':title', $object->getTitle());
            $statement->bindValue(':content', $object->getContent());
            $statement->bindValue(':summary', $object->getSummary());
            $statement->bindValue(':id', $object->getId());

            $statement->bindValue(':userId', $object->getUser()->getId());
            $statement->bindValue(':cTime', $object->getCreationTime());
            $statement->bindValue(':mTime', $object->getModificationTime());
            $statement->bindValue(':aTime', $object->getAccessTime());

            $statement->execute();
            $id = self::lastInsertId();

//             self::commit();

            return $id;
        } catch (PDOException $e) {
//             self::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public static function getAll(): array
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName());

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $objects[$rs->id] = new Article($rs->id, $rs->name, $rs->title, $rs->content, $rs->summary);

                $objects[$rs->id]->setTags(TagDAO::getByArticle($objects[$rs->id]));

                $objects[$rs->id]->setCreationTime($rs->cTime);
                $objects[$rs->id]->setModificationTime($rs->mTime);
                $objects[$rs->id]->setUser(UserDAO::getById($rs->userId));
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $objects;
    }

    public static function getById(string $id): ?Article
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE id=:id');
            $statement->bindParam(':id', $id);

            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $object = new Article($rs->id, $rs->name, $rs->title, $rs->content, $rs->summary);

                $object->setTags(TagDAO::getByArticle($object));

                $object->setCreationTime($rs->cTime);
                $object->setModificationTime($rs->mTime);
                $object->setUser(UserDAO::getById($rs->userId));
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $object;
    }

    public static function getByName(string $name): array
    {
        $objects = array();

         try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE name=:name');
            $statement->bindParam(':name', $name);

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $objects[$rs->id] = new Article($rs->id, $name, $rs->title, $rs->content, $rs->summary);

                $objects[$rs->id]->setTags(TagDAO::getByArticle($objects[$rs->id]));

                $objects[$rs->id]->setCreationTime($rs->cTime);
                $objects[$rs->id]->setModificationTime($rs->mTime);
                $objects[$rs->id]->setUser(UserDAO::getById($rs->userId));
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $objects;
    }

    public static function getByTag(Tag $tag): array
    {
/*
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().', `hasTag` WHERE id=tagId AND tagId=:id');
            $statement->bindParam(':id', $tag->getId());
            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $objects[$rs->id] = new Article($rs->id, $rs->name, $rs->title, $rs->content, $rs->summary);

                $objects[$rs->id]->setTags(TagDAO::getByArticle($objects[$rs->id]));

                $objects[$rs->id]->setCreationTime($rs->cTime);
                $objects[$rs->id]->setModificationTime($rs->mTime);
                $objects[$rs->id]->setUser(UserDAO::getById($rs->userId));
            }
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
*/
    }
}
