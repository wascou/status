<?php
/* ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
 * SOFTWARE NAME: Wascou Software Edition - Status
 * @version 1.0.8
 * @copyright Copyright 2010/2011 - Maxime THOMAS for Wascou Software Edition
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt
 *
 * Copyright Maxime THOMAS ( 2010-12-01 )
 *
 * maxime.thomas@wascou.org
 *
 * This software is a computer program whose purpose is to extend the
 * "eZPublish CMS".
 *
 * This software is governed by the CeCILL license under French law and
 * abiding by the rules of distribution of free software.  You can  use,
 * modify and/ or redistribute the software under the terms of the CeCILL
 * license as circulated by CEA, CNRS and INRIA at the following URL
 * "http: * www.cecill.info".
 *
 * As a counterpart to the access to the source code and  rights to copy,
 * modify and redistribute granted by the license, users are provided only
 * with a limited warranty  and the software's author,  the holder of the
 * economic rights,  and the successive licensors  have only  limited
 * liability.
 *
 * In this respect, the user's attention is drawn to the risks associated
 * with loading,  using,  modifying and/or developing or reproducing the
 * software by the user in light of its specific status of free software,
 * that may mean  that it is complicated to manipulate,  and  that  also
 * therefore means  that it is reserved for developers  and  experienced
 * professionals having in-depth computer knowledge. Users are therefore
 * encouraged to load and test the software's suitability as regards their
 * requirements in conditions enabling the security of their systems and/or
 * data to be ensured and,  more generally, to use and operate it in the
 * same conditions as regards security.
 *
 * The fact that you are presently reading this means that you have had
 * knowledge of the CeCILL license and that you accept its terms.
 *
 *  ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
 */
class wsestPersonManager
{
  /**
   * Get the currently logged in user
   *
   * @param integer $user_id
   * @param boolean $as_object
   *
   * return array State of the person object
   */
    public static function getCurrentUser($user_id = false, $as_object = false)
    {
        if (!isset($_SESSION["WSE_STATUS"]))
        {
            $_SESSION["WSE_STATUS"]=array();
        }

        if (isset($_SESSION["WSE_STATUS"]["CurrentUser"]))
        {
            $person = $_SESSION["WSE_STATUS"]["CurrentUser"];
        }
        else
        {
            if (!$user_id)
            {
                $user_id = eZUser::currentUserID();
            }

            $ini = eZINI::instance();
            $anonymous_id = $ini->variable("UserSettings", "AnonymousUserID");

            if ($user_id == $anonymous_id)
            {
                return false;
            }

            $s = ezcPersistentSessionInstance::get();
            $q = $s->createFindQuery( 'wsestPerson' );
            $q->where( $q->expr->eq( 'ezuser_id', $q->bindValue( $user_id ) ) );
            $persons = $s->find( $q, 'wsestPerson' );

            //If the person related to the user does not exist, we create it
            if (count($persons)==0)
            {
                $person = new wsestPerson();
                $person->setState(array( "ezuser_id" => $user_id ));
                $s->save($person);
            }
            else
            {
                if (count($persons) > 1)
                {
                    eZDebug::writeError("WSE Status - More than one status account for the user '$user_id'.");
                }

                $keys = array_keys($persons);
                $person = $persons[$keys[0]];
            }
            $person = self::getFullUser($person->getState('id'));
        }

        return $person;

    }

    /**
     * Returns the followers for a specified person who is a leader
     *
     * @param integer $person_id Person ID
     * @param boolean $with_profile If true, return the eZ User object
     *
     * @return array Array of person
     */
    public static function getFollowers($person_id, $with_profile=true)
    {
        $s = ezcPersistentSessionInstance::get();
        $person = self::fetchFromID($person_id, true);

        $followers = $s->getRelatedObjects($person, "wsestPerson", "followers");

        if (!$with_profile)
        {
            return $followers;
        }

        $results = array();
        foreach ($followers as $k=>$f)
        {
            $results[$k]=eZUser::fetch($f->getState("ezuser_id"));
        }

        return $results;
    }

    /**
     * Returns the leaders for a specified person who is a follower
     *
     * @param integer $person_id Person ID
     *
     * @return array Array of person
     */
    public static function getLeaders($person_id, $with_profile=true)
    {
        $s = ezcPersistentSessionInstance::get();
        $person = self::fetchFromID($person_id, true);

        $leaders = $s->getRelatedObjects($person, "wsestPerson", "leaders");

        if (!$with_profile)
        {
          return $leaders;
        }

        $results = array();

        foreach ($leaders as $k=>$f)
        {
            $results[$k]=eZUser::fetch($f->getState("ezuser_id"));
        }

        return $results;
    }



    /**
     * Return the number of followers for the provided person id
     * who is a leader
     *
     * @param integer person_id Person ID
     *
     * @return integer Number of followers
     */
    public static function getFollowersCount($person_id)
    {
        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();
        $q->select($q->expr->count("follower_id"));
        $q->from("wsest_followers");
        $q->where($q->expr->eq("leader_id", $person_id));

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();
        $result=0;

        if( count($results)==1)
        {
          $result = $results[0][0];
        }

        return $result;
    }

    /**
     * Return the number of leaders for the provided person id
     * who is a follower
     *
     * @param integer person_id Person ID
     *
     * @return integer Number of leaders people
     */
    public static function getLeadersCount($person_id)
    {
        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();
        $q->select( $q->expr->count("leader_id") );
        $q->from( "wsest_followers" );
        $q->where( $q->expr->eq( "follower_id" ,  $person_id ) );

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();
        $result=0;

        if( count($results)==1)
        {
          $result = $results[0][0];
        }

        return $result;
    }


    /**
     * Add a follower to the provided leader
     * If the follower already exist, nothing is done
     *
     * @param integer $leader_id
     * @param integer $follower_id
     */
    public static function addFollower($leader_id, $follower_id)
    {
        $follower = self::fetchFromID($follower_id, true);
        $leader = self::fetchFromID($leader_id, true);

        $s = ezcPersistentSessionInstance::get();
        $s->addRelatedObject($follower, $leader, "followers");

        //Get data for the email
        $follower_data = eZUser::fetch($follower->getState('ezuser_id'));
        $leader_data = eZUser::fetch($leader->getState('ezuser_id'));

        $ini = eZINI::instance("status.ini");

        $sender_data = array(
            "email"=> $ini->variable("MailSettings", "SenderEmail"),
            "name"=> $ini->variable("MailSettings", "SenderName")
        );

        $receiver_data = array(
            array(
                "email"=>$leader_data->attribute('email'),
              	"name"=>$leader_data->contentobject()->name()
            )
        );

        //Buil the mail
        $tpl = eZTemplate::factory();
        $tpl->setVariable("sender", $follower_data);
        $tpl->setVariable("receiver", $leader_data);
        $mail_content = $tpl->fetch("design:mail/new_follower.tpl");

        $mail = new wsestMailManager($sender_data, $receiver_data, ezi18n( 'status', "You have a new follower." ), $mail_content);

        $mail->send();

        return;
    }


    /**
     * Remove the follower
     *
     * @param integer $leader_id
     * @param integer $follower_id
     */
    public static function removeFollower($leader_id, $follower_id)
    {
        $follower = self::fetchFromID($follower_id, true);
        $leader = self::fetchFromID($leader_id, true);

        $s = ezcPersistentSessionInstance::get();

        try
        {
            $s->removeRelatedObject($follower, $leader, "followers");
        }
        catch (ezcPersistentRelationNotFoundException $e)
        {
            //Do nothing
        }
        return;
    }


    /**
     * Returns a person according to the provided id
     *
     * @param mixed person_id Person ID integer or string id (ezuser login)
     * @param boolean as_object Flag, if true, object is returned
     *
     * @return array or wsestPerson
     */
    public static function fetchFromID($person_id, $as_object = false)
    {
        if (empty($person_id))
        {
            return false;
        }

        $s = ezcPersistentSessionInstance::get();

        if (is_array($person_id))
        {
            $q = $s->createFindQuery("wsestPerson");
            $q->where($q->expr->in("person_id", $p));

            $persons = $s->find( $q, 'wsestPerson' );

            if (!count($persons))
            {
                return false;
            }

        }
        elseif (!is_numeric($person_id))
        {
            $user = eZUser::fetchByName($person_id);

            if(!$user)
            {
                return false;
            }

            $q = $s->createFindQuery("wsestPerson");
            $q->where($q->expr->eq("ezuser_id", $user->ContentObjectID));

            $persons = $s->find( $q, 'wsestPerson' );

            if (!count($persons))
            {
                return false;
            }

            $keys = array_keys($persons);
            $person = $persons[$keys[0]];
        }
        else
        {
            $person = $s->load("wsestPerson", $person_id);
        }

        if ($as_object)
        {
            return $person;
        }

        return $person->getState();
    }


    /**
     * Return the full information about the user, person data and
     * eZPublish data.
     *
     * @param integer $person_id
     *
     * @return array Array of information
     */
    public static function getFullUser($person_id)
    {
        $person = self::fetchFromID($person_id);
        if (!$person)
        {
            return false;
        }

        $person["ezuser"] = eZUser::fetch($person["ezuser_id"]);

        return $person;
    }


    /**
     * Return the followers for a specific person who is a leader
     *
     * @param integer $person_id
     * @param integer $limit
     * @param integer $offset
     *
     * @return array of data
     */
    public static function fetchFollowers($person_id, $limit, $offset)
    {
        $s = ezcPersistentSessionInstance::get();
        $q = $s->createFindQuery('wsestPerson');
        $q->rightJoin( 'wsest_followers', 'wsest_person.id', 'wsest_followers.follower_id' );
        $q->where( $q->expr->eq( "leader_id", $person_id ) );
        $q->limit($limit, $offset);

        //eZDebug::writeDebug($q->getQuery(), "status-fetch-followers-query");

        $persons = $s->find( $q, 'wsestPerson' );

        foreach ($persons as $k=>$p)
        {
            $persons[$k]=self::getFullUser( $p->getState('id') );
        }

        return array('result'=> $persons );
    }

    /**
     * Return the count of followers
     *
     * @param integer $person_id
     *
     * @return integer
     */
    public static function fetchFollowersCount($person_id)
    {
        return array('result' => self::getFollowersCount($person_id));
    }

    /**
     * Return the leaders of a specific person who is a follower
     *
     * @param integr $person_id
     * @param integer $limit
     * @param integer $offset
     *
     * return array of data
     */
    public static function fetchLeaders($person_id, $limit, $offset)
    {
        $result = array();
        $s = ezcPersistentSessionInstance::get();
        $q = $s->createFindQuery('wsestPerson');
        $q->rightJoin( 'wsest_followers', 'wsest_person.id', 'wsest_followers.leader_id' );
        $q->where( $q->expr->eq( "follower_id", $person_id ) );

        $q->limit($limit, $offset);

        //eZDebug::writeDebug($q->getQuery(), "status-fetch-leaders-query");

        $persons = $s->find( $q, 'wsestPerson' );

        foreach ($persons as $k=>$p)
        {
            $persons[$k]=self::getFullUser( $p->getState('id') );
        }

        return array('result' => $persons);
    }

    /**
     * Return the leaders count
     *
     * @param integer $person_id
     *
     * @return integer
     */
    public static function fetchLeadersCount($person_id)
    {
        return array('result' => self::getLeadersCount($person_id) );
    }

    /**
     * Is the first person follow the second ?
     *
     * @param integer $follower_id
     * @param integer $leader_id
     *
     * @return boolean
     */
    public static function isFollower($follower_id, $leader_id)
    {
        $result = false;

        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();
        $q->select( "*" );
        $q->from( "wsest_followers" );
        $q->where(
            $q->expr->eq( "follower_id" ,  $follower_id ),
            $q->expr->eq( "leader_id" ,  $leader_id )
        );

        //eZDebug::writeDebug($q->getQuery(), "status-person_manager-is_follower");

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();

        if( count($results)==1)
        {
            $result = true;
        }

        return array('result' => $result);
    }

    /**
     * Is the first person is followed by the second ?
     *
     * @param integer $leader_id
     * @param integer $follower_id
     *
     * @return boolean
     */
    public static function isLeader($leader_id, $follower_id)
    {
        return self::isFollower($follower_id, $leader_id);
    }

    /**
     * Return all the followers that are also leaders for the person ID.
     *
     * @param integer $person_id
     *
     * @return array
     */
    public static function fetchMutuallyFollowing($person_id)
    {
        $result = array();

        $s = ezcPersistentSessionInstance::get();
        $q = $s->createFindQuery('wsestPerson');

        $q->rightJoin( "wsest_followers f1", $q->expr->eq( "f1.follower_id", "id" ) )
        ->leftJoin( "wsest_followers f2", $q->expr->eq( "f2.leader_id", "id" ) )
        ->where( $q->expr->eq( "f2.follower_id", $person_id ) );

        //See if it's ok with a lot of mutually followers/leaders.
        //$q->limit($limit, $offset);

        //eZDebug::writeDebug($q->getQuery(), "status-fetch-mutually-leaders-query");

        $persons = $s->find( $q, 'wsestPerson' );

        foreach ($persons as $k=>$p)
        {
            $result[$k]=self::getFullUser( $p->getState('id') );
        }

        return array('result' => $result);
    }

    /**
     * Return the number of mutual followers
     *
     * @param integer $person_id
     *
     * @return array
     */
    public static function fetchMutuallyFollowingCount($person_id)
    {
        $result = array();

        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();

        $q2 = $q->subSelect();
        $q2->select( "leader_id")->from("wsest_followers")->where($q->expr->eq("follower_id", $person_id) );

        $q->select( $q->expr->count("leader_id") );
        $q->from("wsest_followers");

        $q->where(
            $q->expr->eq( "leader_id", $person_id ),
            $q->expr->in( "follower_id", $q2)
        );

        //eZDebug::writeDebug($q->getQuery(), "status-fetch-mutual-followers-count-query");

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();
        $result=0;

        if( count($results)==1)
        {
          $result = $results[0][0];
        }

        return array('result' => $result);
    }

    /**
     * Return true if the two persons are following each other
     *
     * @param integer $person_id
     * @param integer $person_id2
     *
     * @return array
     */
    public static function isMutuallyFollowing($person_id, $person_id2)
    {
        $result = false;

        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();

        $q->select( $q->expr->count("leader_id") )->from("wsest_followers");

        //Relation is in the both ways : person follows person2 and reverse

        $q->where(
            $q->expr->lOr(
                $q->expr->lAnd(
                    $q->expr->eq( "follower_id", $person_id),
                    $q->expr->eq( "leader_id", $person_id2)
                ),
                $q->expr->lAnd(
                    $q->expr->eq( "follower_id", $person_id2),
                    $q->expr->eq( "leader_id", $person_id)
                )
            )
        );

        //eZDebug::writeDebug($q->getQuery(), "status-fetch-is-mutual-follower-query");

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();
        $result=0;

        if( count($results)==1 and $results[0][0] == 2)
        {
            $result = true;
        }

        return array('result' => $result);
    }


    /**
     * Update the last_update field which allows to now when was the last action
     * from the person.
     *
     * @param $person_id
     */
    public static function updateLastUpdate($person_id)
    {
        $s = ezcPersistentSessionInstance::get();
        $person = self::fetchFromID($person_id, true);

        $state = $person->getState();
        $state["last_update"]=time();
        $person->setState($state);

        $s->update($person);
    }


	/**
     * Perform the indexation of the provided wsestPerson object
     *
     * @param wsestPerson $person
     */
    public static function index($person, $commit = true)
    {
        $item = wsestSearchPerson::embed($person);
        $sh = new wsestSearchHandler();
        $sh->index($item);
        if ( $commit )
        {
            $sh->commit();
        }
    }


    /**
     * Return the number of persons in the system
     *
     * @return integer
     */
    public static function getPersonsCount()
    {
        $db = ezcDbInstance::get();
        $q = $db->createSelectQuery();
        $q->select($q->expr->count("id"));
        $q->from("wsest_person");

        eZDebug::writeDebug($q->getQuery(), "status-person_manager-get_persons_count");

        $statement = $q->prepare();
        $statement->execute();
        $results = $statement->fetchAll();
        $result=0;

        if( count($results)==1)
        {
          $result = $results[0][0];
        }

        return $result;
    }

    /**
     *	Returns an array of wsestPersons according to the offset and limit.
     *
     * @param integer $offset
     * @param integer $limit
     *
     * @return array of wsestPerson
     */
    public static function getPersons($offset = 0, $limit = 100 )
    {
        $s = ezcPersistentSessionInstance::get();
        $q = $s->createFindQuery("wsestPerson");
        $q->limit($limit, $offset);

		eZDebug::writeDebug($q->getQuery(), "status-person_manager-get-persons");
		$persons = $s->find( $q, 'wsestPerson' );

		return $persons;
    }


}

?>