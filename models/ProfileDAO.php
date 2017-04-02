<?php

class ProfileDAO extends DatedDAO
{
    const TABLE_NAME = 'Profile';

    public static function create(&$object)
    {
        parent::create($object);

        try {
            $statement = self::prepare('INSERT INTO '.self::getTableName().' (userId, pseudonym, firstName, lastName, mediaId) values (:userId, :firstName, :lastName, :mediaId)');
            $statement->bindValue(':userId', $object->getUser()->getId());
            $statement->bindValue(':pseudonym', $object->getPseudonym());
            $statement->bindValue(':firstName', $object->getFirtName());
            $statement->bindValue(':lastName', $object->getLastName());
            $statement->bindValue(':mediaId', ($object->getMedia()) ? $object->getMedia()->getId() : null);

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
            $statement = self::prepare('UPDATE '.self::getTableName().' SET pseudonym=:pseudonym, firstName=:firstName, lastName=:lastName WHERE userId=:userId');
            $statement->bindValue(':userId', $object->getUser()->getId());
            $statement->bindValue(':pseudonym', $object->getPseudonym());
            $statement->bindValue(':firstName', $object->getFirstName());
            $statement->bindValue(':lastName', $object->getLastName());
            $statement->bindValue(':mediaId', ($object->getMedia()) ? $object->getMedia()->getId() : null);

            $statement->execute();
            $id = self::lastInsertId();

//             self::commit();

            return $id;
        } catch (PDOException $e) {
//             self::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public static function getAll(): array // @PARTIAL
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName());

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                // int $id, User $user, string $pseudonym = null, string $firstName = null, string $lastName = null
                $objects[$rs->userId] = new Profile(UserDAO::getById($rs->userId), $rs->pseudonym, $rs->firstName, $rs->lastName);
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $objects;
    }

    public static function getById(string $userId): ?Profile
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE userId=:userId');
            $statement->bindParam(':userId', $userId);

            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $object = new Profile(UserDAO::getById($rs->userId), $rs->pseudonym, $rs->firstName, $rs->lastName);
                if($rs->mediaId)
                    $object->setMedia(MediaDAO::getById($rs->mediaId));
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $object;
    }

    public static function getByUser(User $user): ?Profile
    {
        return self::getById($user->getId());
    }

/*
    public static function getByName(string $name): array
    {
        $objects = array();

         try {
            $statement = self::prepare('SELECT * FROM '.self::getTableName().' WHERE name=:name');
            $statement->bindParam(':name', $name);

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $objects[$rs->userId] = new Profile(UserDAO::getById($rs->userId), $rs->pseudonym, $rs->firstName, $rs->lastName);
            }
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        return $objects;
    }
*/
}
