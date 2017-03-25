<?php

class UserDAO extends DatedDAO
{
    const TABLE_NAME = 'User';

    public static function create(&$user)
    {
        try {
            $statement = self::prepare('INSERT INTO '.self::getTableName().' (emailAddress, passwordHash, cTime, mTime, aTime, sessionHash) values (:emailAddress, :passwordHash, :cTime, :mTime, :aTime, :sessionHash)');
            $statement->bindValue(':emailAddress', $user->getEmailAddress());
            $statement->bindValue(':passwordHash', $user->getPasswordHash());

            $statement->bindValue(':cTime', $user->getCreationTime());
            $statement->bindValue(':mTime', $user->getModificationTime());
            $statement->bindValue(':aTime', $user->getAccessTime());
            $statement->bindValue(':sessionHash', $user->getSessionHash());

            $statement->execute();
            $user->setId(self::getInstance()->lastInsertId());

            foreach($user->getGroups() as $group)
                self::addInGroup($user, $group);

            return $user->getId();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function update(&$user)
    {
        try {
            $statement = self::prepare('UPDATE '.self::getTableName().' SET emailAddress=:emailAddress, passwordHash=:passwordHash, cTime=:cTime, mTime=:mTime, aTime=:aTime, sessionHash=:sessionHash WHERE id=:id');
            $statement->bindValue(':id', $user->getId());
            $statement->bindValue(':emailAddress', $user->getEmailAddress());
            $statement->bindValue(':passwordHash', $user->getPasswordHash());

            $statement->bindValue(':cTime', $user->getCreationTime());
            $statement->bindValue(':mTime', $user->getModificationTime());
            $statement->bindValue(':aTime', $user->getAccessTime());
            $statement->bindValue(':sessionHash', $user->getSessionHash());

            $statement->execute();

            self::_updateGroups($user);

            return self::getInstance()->lastInsertId();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function getAll(): array
    {
        $objects = array();

        try {
            $statement = self::prepare('SELECT id, emailAddress FROM '.self::getTableName());

            $statement->execute();

            while ($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $objects[$rs->login] = new User($rs->id, $rs->emailAddress);
            }
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $objects;
    }

    public static function getById(int $id): ?User
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT emailAddress, passwordHash, cTime, mTime, aTime, sessionHash FROM '.self::getTableName().' WHERE id=:id');
            $statement->bindParam(':id', $id);
            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $object = new User($id, $rs->emailAddress, $rs->passwordHash);
                $object->setGroups(GroupDAO::getByUser($object));

                $object->setCreationTime($rs->cTime);
                $object->setModificationTime($rs->mTime);
                $object->setAccessTime($rs->aTime);
            }
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $object;
    }

    public static function getByLogin(string $login): ?User
    {
        $object = null;

        try {
            $statement = self::prepare('SELECT id, passwordHash, cTime, mTime, aTime, sessionHash FROM '.self::getTableName().' WHERE emailAddress=:login');
            $statement->bindParam(':login', $login);
            $statement->execute();

            if($rs = $statement->fetch(PDO::FETCH_OBJ)) {
                $object = new User($rs->id, $login, $rs->passwordHash);
                $object->setGroups(GroupDAO::getByUser($object));

                $object->setCreationTime($rs->cTime);
                $object->setModificationTime($rs->mTime);
                $object->setAccessTime($rs->aTime);
            }
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }

        return $object;
    }

    public static function addInGroup(User $user, Group $group) {
        try {
            $statement = self::prepare('INSERT INTO `inGroup` (`userId`, `groupId`) VALUES (:userId, :groupId)');
            $statement->bindValue(':userId', $user->getId());
            $statement->bindValue(':groupId', $group->getId());

            $statement->execute();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    public static function removeFromGroup(User $user, Group $group) {
        try {
            $statement = self::prepare('DELETE FROM `inGroup` WHERE `userId`=:userId AND `groupId`=:groupId)');
            $statement->bindValue(':userId', $user->getId());
            $statement->bindValue(':groupId', $group->getId());

            $statement->execute();
        } catch (PDOException $e) {
            die(__METHOD__.' : '.$e->getMessage().'<br />');
        }
    }

    private static function _updateGroups(User $user)
    {
        $news = $user->getGroups();
        $olds = GroupDAO::getByUser($user);

        foreach(array_diff($olds, $new) as $removed)
            self::removeFromGroup($user, $removed);

        foreach(array_diff($news, $olds) as $added)
            self::addInGroup($user, $added);
    }
}
