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
class wsestStatusManager
{
    public $always_commit = true;
    public $search_engine;

    /**
     * Allows to se set an auto commit mode for all actions
     * This is useful when you want to do a sequence of action
     * that relies on the index of the search engine.
     *
     * For example : you want to save several status with one piece
     * of code, you have to commit at the end and not status by status
     *
     * Two cases :
     *
     * 1 - Unitary save
     * <code>
     * 		wsestStatusManager::save($status);
     * </code>
     *
     * 2 - Several saves in one shot
     * <code>
     * 		wsestStatusManager::getInstance(false);
     * 		...
     * 			wsestStatusManager::save($status);
     *  	...
     * 		wsestStatusManager::commit();
     * </code>
     *
     *
     */
    public static function getInstance($always_commit = true)
    {
        if (isset($GLOBALS["WSE_STATUS"]["StatusManager"]) and $GLOBALS["WSE_STATUS"]["StatusManager"] instanceof wsestStatusManager )
        {
            return $GLOBALS["WSE_STATUS"]["StatusManager"];
        }

        $sm = new wsestStatusManager();
        $sm->always_commit = $always_commit;
        $sm->search_engine = new wsestSearchHandler();

        $GLOBALS["WSE_STATUS"]["StatusManager"] = $sm;

        return $sm;
    }


    /**
     * Commit all the actions in the search engine
     * @see wsestStatusManager::getInstance()
     */
    public function commit()
    {
        $this->search_engine->commit();
    }



    /**
     * Save and index the status
     * Not safe, use commit after use
     *
     * @param wsestStatus $status
     */
    private static function save($status)
    {
        if (!($status instanceof wsestStatus))
        {
            throw new Exception("You cannot save an object that is not a wsestStatus." );
        }

        $s = ezcPersistentSessionInstance::get();
        $s->save($status);

        //eZDebug::writeDebug($status);

        $item = wsestSearchItem::embed($status);

        $instance = self::getInstance();
        $instance->search_engine->index($item);

        //By default, the 'status save' commits the 'update of the search engine'
        if ($instance->always_commit)
        {
            $instance->commit();
        }
    }



    /**
     * Fetch a status regarding to the provided status ID
     *
     * @param integer $status_id Status ID
     * @param boolean $as_object if true return the object,
     * else return an array
     *
     * @return mixed An array of attributes or a wsestStatus
     */
    public static function fetchFromID($status_id, $as_object)
    {
        if (empty($status_id))
        {
            return false;
        }

        $s = ezcPersistentSessionInstance::get();

   	    $status = $s->load("wsestStatus", $status_id);

   	    if ($as_object)
    	{
    		return $status;
    	}

    	return $status->getState();
    }


	/**
	 * Fetch for the status
	 *
	 * @param integer $person_id Person ID
	 * @param integer $scope Scope of the status (all or only own)
	 *
	 * @return a fetch array of status
	 */
	public function fetch($person_id, $scope, $limit=false, $offset=false)
	{
		$persons = array();
		$persons_objects = array();

		if (!$person_id)
		{
			eZDebug::writeError("An error occured : $person_id must be an integer", "Fetch status");
			return false;
		}

		if ($scope == wsestStatus::WSEST_STATUS_SCOPE_ALL)
		{
			$persons_objects = wsestPersonManager::getLeaders($person_id, false);
			//eZDebug::writeDebug($persons_objects, "status-status_manager-fetch");

			foreach ($persons_objects as $po)
		    {
		        $persons[]=$po->getState("id");
		    }
		}

		$results = self::getStatus(array(
			"owner_id"			 => $person_id,
		    "scope"				 => $scope,
			"persons"            => $persons,
			"link_persons"       => true,
			"link_favorites"     => true,
			"link_spreads"       => true,
			"offset"             => $offset,
			"limit"              => $limit
		));

		//eZDebug::writeDebug($results);

		return array('result' => $results);
	}

	/**
	 * Get the number of status
	 *
	 * @param integer $person_id Person ID
	 * @param integer $scope Scope of the status (all or only own)
	 *
	 * @return a fetch integer
	 */
	public function fetchCount($person_id, $scope)
	{
		$persons = array();
		$persons_objects = array();

		if (!$person_id)
		{
			eZDebug::writeError("An error occured : $person_id must be an integer", "Fetch status - favorites");
			return false;
		}

	    if ($scope == wsestStatus::WSEST_STATUS_SCOPE_ALL)
		{
			$persons_objects = wsestPersonManager::getLeaders($person_id, false);
		}

		$persons_objects[]=wsestPersonManager::fetchFromID($person_id, true);

		foreach ($persons_objects as $po)
		{
		    $persons[]=$po->getState("id");
		}

		$results = self::getStatusCount($persons);

		return array('result' => $results);
	}



	/**
	 * Return the favorites of the person
	 *
	 * @param integer $person_id
	 * @param integer $limit
	 * @param integer $offset
	 *
	 * @return a fetch array of status
	 */
	public static function fetchFavorites($person_id, $limit=10, $offset=0)
	{
		$results = array();
		$s = ezcPersistentSessionInstance::get();

		if (!$person_id)
		{
			eZDebug::writeError("An error occured : $person_id must be an integer", "Fetch status - favorites");
			return false;
		}

		$q = $s->createFindQuery("wsestStatus");
		$q->rightJoin( 'wsest_favorite', 'wsest_status.id', 'wsest_favorite.status_id' );

		$q->where($q->expr->eq("wsest_favorite.person_id", $person_id) );

		$q->limit($limit, $offset);
        $q->orderBy("date", ezcQuerySelect::DESC);

       //eZDebug::writeDebug( $q->getQuery(), "status-status_manager-fetchFavorites" );

        $status = $s->find($q, "wsestStatus");

		$params = array(
			"link_persons" => true,
			"link_favorites" => true,
			"link_spreads" => true,
		);

		$results = self::enhanceStatusList($status, $params);

		//eZDebug::writeDebug($results, "Fetch - status - favorites");

		return array('result' => $results);
	}


	/**
	 * Count the number of favorites
	 *
	 * @param integer $person_id
	 *
	 * @return a fetch integer
	 */
	public static function fetchFavoritesCount($person_id)
	{
		$db = ezcDbInstance::get();
		$q = $db->createSelectQuery();
		$q->select($q->expr->count("person_id"));
		$q->from("wsest_favorite");
		$q->where($q->expr->eq("person_id", $person_id));

		//eZDebug::writeDebug( $q->getQuery());

		$statement = $q->prepare();
		$statement->execute();
		$results = $statement->fetchAll();
		$result=0;

		if( count($results)==1)
		{
			$result = $results[0][0];
		}

		//eZDebug::writeDebug($results, "Fetch - status - favorites_count");

		return array('result' => $result);
	}





	/**
	 * Return the status according the provided params
	 *
	 * @param array $params :
	 * - offset : integer, by default 0
	 * - limit : integer, by default 0
	 *
	 * - owner_id : Id of the owner
	 * - scope : Scope of the request
	 * - persons : array of Person IDs, by default empty, required.
	 * The last item of this array is the main person (others are
	 * leaders).
	 * - as_object : boolean, by default false. If true return the
	 * status as an object of type wsestStatus
	 * - with_discussion : boolean, by default false. If true return
	 * the status replied by others
	 *
	 * - link_persons : boolean, by default false. If true return the
	 * persons linked to the status
	 * - link_favorites : boolean, by default false. If true return the
	 * favorites flag in 'is_favorite' foreach status
	 * - link_spreads : boolean, by default false. If true return the
	 * spread flag in 'is_spread' foreach status
	 *
	 * @return array List of wsestStatus
	 *
	 */
	public static function getStatus($params)
	{
		$s = ezcPersistentSessionInstance::get();
		$q = $s->createFindQuery("wsestStatus");
		$where_clauses=array();

		if (!$params["owner_id"])
		{
			eZDebug::writeError("No owner provided for the fetch(status, status, ...).", "Error during the fetch");
			return array();
		}

		//eZDebug::writeDebug($params, "Params for the query.");

		$q->orderBy("date", ezcQuerySelect::DESC);

		if (isset($params["limit"]))
		{
			if (isset($params["offset"]))
			{
				$q->limit($params["limit"], $params["offset"]);
			}
			else
			{
				$q->limit($params["limit"]);
			}
		}

		if ($params["scope"] == wsestStatus::WSEST_STATUS_SCOPE_ALL)
		{
		    if (count($params["persons"]))
    		{
                //Discussions for the owner
    		    $discussions = self::fetchDiscussions($params["owner_id"], false);

    		    if (count($discussions))
    		    {
    		        $where_clauses[]=$q->expr->lOr(
        		        //1 - Status belongs to the person
        		        $q->expr->eq("person_id", $params["owner_id"]),

        		        //2 - Status belongs to leaders and are public
        		        $q->expr->lAnd(
        		            //Person ID must be in the array of persons
        		            $q->expr->in("person_id", $params["persons"]),
        		            //Status must be normal
        		            $q->expr->eq("status", wsestStatus::WSEST_STATUS_STATUS_NORMAL)
        		        ),

        		        //3 - Status of current discussions
        		        $q->expr->in("discussion_id", $discussions ),

        		        //4 - Status are retweeted by the persons
        		        $q->expr->lAnd(
        		            //Via ID is the person ID
        		            $q->expr->in("via_id", $params["owner_id"]),
        		            //Person ID is in the person array
        		            $q->expr->in("person_id", $params["persons"]),
        		            //Status must broadcasted
        		            $q->expr->eq("status", wsestStatus::WSEST_STATUS_STATUS_BROADCAST)
        		        )
        		    );
    		    }
    		    else
    		    {
    		        //Status that belongs to the person
    		        $params["persons"][]=$params["owner_id"];
    		        $where_clauses[]=$q->expr->in("person_id", $params["persons"]);
    		    }

    		    //And the privacy is public
    		    $where_clauses[]=$q->expr->eq("privacy", wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC);
    		}
    		else
    		{
    		    $where_clauses[]= $q->expr->eq("person_id", $params["owner_id"]);
    		}
		}
    	elseif ($params["scope"] == wsestStatus::WSEST_STATUS_SCOPE_OWN)
    	{
    	    $where_clauses[]= $q->expr->eq("person_id", $params["owner_id"]);
       	}

		if (count($where_clauses))
		{
			$q->where($where_clauses);
		}

		//eZDebug::writeDebug($q->getQuery(), "Query for status");
		//print($q->getQuery());

		$status = $s->find( $q, 'wsestStatus' );

		//eZDebug::writeDebug($status, "Results of the query");

		return self::enhanceStatusList($status, $params);

	}

	/**
	 * Enhance the provided status array
	 *
	 * @param array $status wsestStatus
	 * @param array $params array Params to enhance the array list :
	 * - link_persons : boolean, by default true. If true return the
	 * persons
	 * - link_favorites : boolean, by default true. If true return the
	 * favorites flag in 'is_favorite' foreach status
	 * - link_spreads : boolean, by default true. If true return the
	 * spread flag in 'is_spread' foreach status
	 *
	 * @return mixed Either an array of wsestStatus either an array of
	 * data
	 */
	public static function enhanceStatusList($status, $params =
	    array(
		    "link_persons"        => true,
		    "link_favorites"      => true,
		    "link_spreads"		  => true,
        )
    )
	{
		$results = array();

		if (count($status)==0)
		{
			return $results;
		}

		foreach ($status as $k => $st)
		{
			$results[$k]=$st->getState();
		}

		if (isset($params["link_persons"]) and $params["link_persons"])
		{
			foreach ($status as $k => $st)
			{
				$p_id = $results[$k]["person_id"];
				$results[$k]["person"]= wsestPersonManager::getFullUser($p_id);
			}
		}

		//Manage the favorite flag
		if (isset($params["link_favorites"]) and $params["link_favorites"])
		{
			$current_user = wsestPersonManager::getCurrentUser();

			foreach ($status as $k => $st)
			{
				$results[$k]["is_favorite"]= $st->isFavorite($current_user["id"]);
			}
		}

		//Manage the spread flag
		if (isset($params["link_spreads"]) and $params["link_spreads"])
		{
			foreach ($status as $k => $st)
			{
				$results[$k]["is_spread"]= $st->isSpread();
				if ($results[$k]["is_spread"])
				{
					$results[$k]["spread_data"]=wsestPersonManager::getFullUser($st->getState("via_id"));
				}
			}
		}
		return $results;
	}


	/**
	 * Count the status for the provided person_id
	 *
	 * @param array $persons Array of Person IDs
	 *
	 * @return integer Number of status
	 */
	public static function getStatusCount($persons)
	{
		$db = ezcDbInstance::get();
		$q = $db->createSelectQuery();
		$q->select($q->expr->count("id"));
		$q->from("wsest_status");
		$q->where($q->expr->in("person_id", $persons));

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
	 * Allow to add a status for the concerned person
	 *
	 * @param integer $person_id
	 * @param string $message
	 *
	 */
	public static function addStatus($person_id, $message)
	{
        $status = new wsestStatus();

        $status->setState(array(
            "person_id"     => $person_id,
            "status"        => wsestStatus::WSEST_STATUS_STATUS_NORMAL,
            "date"          => time(),
            "privacy"       => wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC,
            "message"       => $message
        ));

        self::save($status);

        wsestPersonManager::updateLastUpdate($person_id);

	}

	/**
	 * Parse the status to find special words :
	 * - words prefixed by @ : people
	 * - words prefixed by # : subjects
	 * And delete all Javascript and HTML.
	 *
	 * @param string $message
	 *
	 * @return string Message transformed
	 */
	public static function parse($message)
	{
		$exp = "~(#\w+)~";
		$matches = null;
		$match_count = preg_match_all($exp, $message, $matches);
		if ($match_count)
		{
			array_shift($matches);

			foreach($matches[0] as $m)
			{
				$uri = '/status/search/'.substr($m,1);
				eZURI::transformURI($uri);
				$exp = "~$m~";
				$replacement = '<a href="'.$uri.'">'.$m.'</a>';
				$message = preg_replace($exp, $replacement, $message);
			}
		}

		$exp = "~(@\w+)~";
		$matches = null;
		$match_count = preg_match_all($exp, $message, $matches);
		if ($match_count)
		{
			array_shift($matches);

			foreach($matches[0] as $m)
			{
				$uri = '/status/profile/'.substr($m,1);
				eZURI::transformURI($uri);
				$exp="~$m~";
				$replacement = '<a href="'.$uri.'">'.$m.'</a>';
				$message = preg_replace($exp, $replacement, $message);
			}
		}

		return $message;
	}



	/**
	 * Add as a favorite the status to the person
	 *
	 * @param integer $person_id Person ID
	 * @param integer $status_id Status ID
	 */
    public static function addFavorite($person_id, $status_id)
    {
        if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        //Check if the favorite already exists
        $status = self::fetchFromID($status_id, true);
        if ($status->isFavorite($person_id))
        {
            wsestPersonManager::updateLastUpdate($person_id);
            return;
        }

        $person = wsestPersonManager::fetchFromID($person_id, true);

        $s = ezcPersistentSessionInstance::get();
        $s->addRelatedObject($status, $person);

        wsestPersonManager::updateLastUpdate($person_id);
    }


    /**
     * Remove a favorite according to the person ID and status id provided
     *
     * @param integer $person_id
     * @param integr $status_id
     * @throws Exception if bad type params are provided
     */
    public static function removeFavorite($person_id, $status_id)
    {
        if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        //Check if the status is a favorite
        $status = self::fetchFromID($status_id, true);
        if ($status and $status->isFavorite($person_id))
        {
            $person = wsestPersonManager::fetchFromID($person_id, true);
            $s = ezcPersistentSessionInstance::get();
            $s->removeRelatedObject($status, $person);

            wsestPersonManager::updateLastUpdate($person_id);
        }
    }


    /**
     * Remove a status if it belongs to the provided person.
     *
     * @param integer $person_id
     * @param integer $status_id
     * @throws Exception if bad types params are provided
     */
    public static function removeStatus($person_id, $status_id)
    {
        if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        //Check if the status exist and if it belongs to the concerned person
        $status = self::fetchFromID($status_id, true);
        if ($status and $status->getState("person_id") == $person_id)
        {
            $s = ezcPersistentSessionInstance::get();
            $s->delete($status);

            wsestPersonManager::updateLastUpdate($person_id);
        }
    }


    /**
     * Spread the status to all the followers, original
     * person status id is saved in status.via_id.
     *
     * @param integer $person_id
     * @param integer $status_id
     */
    public static function spread($person_id, $status_id)
    {
    	if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        $status = self::fetchFromID($status_id, true);

        if ($status)
        {
        	$status_to_spread = new wsestStatus();
        	$status_to_spread->setState(array(
        		"person_id"		=>$status->getState("person_id"),
        		"via_id"		=>$person_id,
        		"status" 		=>wsestStatus::WSEST_STATUS_STATUS_BROADCAST,
        	    "privacy"		=>wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC,
        		"date"			=>time(),
        		"message"		=>$status->getState("message"),
        	));

  			self::save($status_to_spread);

  			wsestPersonManager::updateLastUpdate($person_id);

  			//Apply the template for the mail
            $sender = wsestPersonManager::getFullUser( $person_id );
            $receiver = wsestPersonManager::getFullUser( $status->getState("person_id") );

            $tpl = eZTemplate::factory();
            $tpl->setVariable("receiver", $receiver);
            $tpl->setVariable("sender", $sender);
            $tpl->setVariable("message", $status->getState("message"));

            $mail_content = $tpl->fetch("design:mail/new_spread.tpl");

            //Build the mail
            $ini = eZINI::instance("status.ini");

            $sender_data = array(
                "email"=> $ini->variable("MailSettings", "SenderEmail"),
                "name"=> $ini->variable("MailSettings", "SenderName")
            );

            $receiver_data = array(
                array(
                    "email" => $receiver["ezuser"]->attribute("email"),
                    "name" => $receiver["ezuser"]->contentobject()->name()
                )
            );

            $mail = new wsestMailManager( $sender_data, $receiver_data, ezi18n( "status/mail", "You have been spread" ), $mail_content );

            //Send it
            $mail->send();


        }
    }



    /**
     * Unspread the status and check if it belongs to the provided person_id
     *
     * @param integer $person_id
     * @param integer $status_id
     * @throws Exception
     */
    public static function unspread($person_id, $status_id)
    {
        if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        $status = self::fetchFromID($status_id, true);

        if ($status and $status->getState("via_id") == $person_id)
        {
  			$s = ezcPersistentSessionInstance::get();
  			$s->delete($status);

  			wsestPersonManager::updateLastUpdate($person_id);
        }

    }


	/**
	 * Extract the person (@term) from a message and make each term
	 * unique.
	 *
	 * @param string $message Message of the status
	 * @param string $owner Current owner of the status
	 *
	 * @return array Array of terms
	 */
    public static function extractPersons($message, $owner)
	{
	    //TODO check if related to the parse() function / Maybe to clean up
		$exp = "~(@\w+)~";
		$matches = null;
		$match_count = preg_match_all($exp, $message, $matches);
		if ($match_count)
		{
			$matches[0][]="@".$owner;
			return array_unique($matches[0]);
		}
		return array();
	}
    public static function extractSubjects($message)
	{
	    //TODO check if related to the parse() function / Maybe to clean up
		$exp = "~(#\w+)~";
		$matches = null;
		$match_count = preg_match_all($exp, $message, $matches);
		if ($match_count)
		{
			return array_unique($matches[0]);
		}
		return array();
	}


	/**
	 * Reply to the $status_id with the message and link it to
	 * a specific discussion
	 *
	 * @param integer $person_id Person ID
	 * @param integer $status_id Status ID
	 * @param string $message URL encoded message
	 */
	public static function reply($person_id, $status_id, $message)
	{
		if (!is_numeric($person_id))
        {
            throw new Exception("The person_id is not numeric.");
        }
        elseif (!is_numeric($status_id))
        {
            throw new Exception("The status_id is not numeric.");
        }

        $s = ezcPersistentSessionInstance::get();
        $current_status = $s->load("wsestStatus", $status_id);

        //If the current status has a discussion id, we use it
        if ($current_status->getState("discussion_id"))
        {
        	$discussion_id = $current_status->getState("discussion_id");
        }
        else
        {
            //The current status is the first of the discussion
        	//We update it before replying
	        $params = $current_status->getState();
	        $params["discussion_id"]=$params["id"];
	        $current_status->setState($params);
	        $s->update($current_status);
	        $discussion_id = $params["id"];
     	}

     	//Create the reply
        $reply = new wsestStatus();

        $params = array(
        	"person_id" 	=> $person_id,
            "receiver_id"	=> $current_status->getState("person_id"),
        	"discussion_id"	=> $discussion_id,
        	"status"		=> wsestStatus::WSEST_STATUS_STATUS_REPLY,
        	"date"			=> time(),
            "privacy"		=> wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC,
        	"message"		=> urldecode($message)
        );
        $reply->setState($params);
	    self::save($reply);

	    wsestPersonManager::updateLastUpdate($person_id);

	    //Apply the template for the mail
        $sender = wsestPersonManager::getFullUser($person_id);
        $receiver = wsestPersonManager::getFullUser($current_status->getState("person_id"));

        $tpl = eZTemplate::factory();
        $tpl->setVariable("receiver", $receiver);
        $tpl->setVariable("sender", $sender);
        $tpl->setVariable("message", $message);
        $tpl->setVariable("discussion_id", $discussion_id);

        $mail_content = $tpl->fetch("design:mail/new_response.tpl");

        //Build the mail
        $ini = eZINI::instance("status.ini");

        $sender_data = array(
            "email"=> $ini->variable("MailSettings", "SenderEmail"),
            "name"=> $ini->variable("MailSettings", "SenderName")
        );

        $receiver_data = array(
            array(
                "email" => $receiver["ezuser"]->attribute("email"),
                "name" => $receiver["ezuser"]->contentobject()->name()
            )
        );

        $mail = new wsestMailManager( $sender_data, $receiver_data, ezi18n( "status/mail", "You have a new response"), $mail_content );

        //Send it
        $mail->send();


	    return $discussion_id;
	}


	/**
	 * Return the discussion IDs of the person
	 *
	 * @param integer $person_id Person ID : owner of the discussion
	 * @param boolean $as_object True by default. If false, return an array
	 * of IDs
	 * @param integer $privacy by default set to public
	 *
	 * @return array Discussion IDs
	 */
	public static function fetchDiscussions($person_id, $as_object = true, $privacy = wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC)
	{
        $s = ezcPersistentSessionInstance::get();
		$q = $s->createFindQuery("wsestStatus");
		$q->groupBy("discussion_id");
		$q->orderBy("date", ezcQuerySelect::DESC);

		$where_clauses=array(
		    $q->expr->not($q->expr->isNull("discussion_id")),
		    $q->expr->eq("person_id", $person_id),
		    $q->expr->eq("privacy", $privacy),
		);

		$q->where($where_clauses);

		//eZDebug::writeDebug($q->getQuery(), "status-status-fetch-discussions");

		$results = $s->find( $q, 'wsestStatus' );

		if ($as_object)
		{
		    return self::enhanceStatusList($results);
		}
		return array_keys($results);
	}


	/**
	 * Fetch a full discussion by this ID
	 *
	 * @param integer $discussion_id Discussion ID
	 * @param integer $privacy Privacy level
	 *
	 * @return array of enhanced wsestStatus
	 */
	public static function fetchDiscussion($discussion_id, $privacy = wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC)
	{
        $s = ezcPersistentSessionInstance::get();
		$q = $s->createFindQuery("wsestStatus");

		$where_clauses=array(
		    $q->expr->eq("discussion_id", $discussion_id),
		    $q->expr->eq("privacy", $privacy)
		);

		$q->where($where_clauses);
		$q->orderBy("date", ezcQuerySelect::DESC);

		$results = $s->find( $q, 'wsestStatus' );
		return self::enhanceStatusList($results);
	}

	/**
	 * Fetch the number of messages (private discussions) for a person
	 *
	 * @param integer $person_id Person ID
	 *
	 * @return a fetch integer
	 */
	public static function fetchMessagesCount($person_id)
	{
        return self::fetchDiscussionsCount($person_id, wsestStatus::WSEST_STATUS_PRIVACY_PRIVATE);
	}

	/**
	 * Fetch the number of discussions for a person
	 *
	 * @param integer $person_id Person ID
	 * @param integer $privacy By default public
	 *
	 * @return a fetch integer
	 */
	public static function fetchDiscussionsCount($person_id, $privacy = wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC)
	{
        $db = ezcDbInstance::get();
		$q = $db->createSelectQuery();
		$q->select($q->expr->count("id"));
		$q->from("wsest_status");
		$q->where(
		    $q->expr->eq("person_id", $person_id),
		    $q->expr->eq("privacy", $privacy)
		);

		//eZDebug::writeDebug($q->getQuery(),"status-status-fetch-discussions-count");

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
	 * Fetch actors of a discussion
	 *
	 * @param integer $discussion_id
	 */
	public static function fetchDiscussionActors($discussion_id)
	{
	    $s = ezcPersistentSessionInstance::get();

	    $q = $s->createFindQuery("wsestStatus");
		$q->groupBy("person_id");
		$q->where($q->expr->eq("discussion_id", $discussion_id));

		//eZDebug::writeDebug($q->getQuery());
		$results = $s->find( $q, 'wsestPerson' );
		//eZDebug::writeDebug($results);

		foreach ($results as $k=>$r)
		{
		    $results[$k]=wsestPersonManager::getFullUser($r->getState("person_id"));
		}

		return $results;
	}

	/**
	 * Send a message from a person (sender) to another person (receiver)
	 *
	 * @param integer $sender_id Sender person ID
	 * @param integer $receiver_id Receiver person ID
	 * @param string $message Text of the message
	 * @param integer $discussion_id If the discussion is already started,
	 * provide the discussion ID, else the discussion id will be set to the
	 * new status ID.
	 */
	public static function addMessage($sender_id, $receiver_id, $message,  $discussion_id = false)
	{
		if (!is_numeric($sender_id ))
		{
			throw new Exception("The sender id is not numeric.");
		}
		if (!is_numeric($receiver_id))
		{
		    throw new Exception("The receiver id is not numeric.");
		}
		if (empty($message))
		{
		    throw new Exception("The mesage is empty.");
		}

		if ($sender_id === $receiver_id)
		{
			throw new Exception("The sender and the receiver can not be the same.");
		}

		$s = ezcPersistentSessionInstance::get();

        //Create the status
        $reply = new wsestStatus();
        $message = urldecode($message);

        $params = array(
        	"person_id" 	=> $sender_id,
            "receiver_id"	=> $receiver_id,
        	"status"		=> wsestStatus::WSEST_STATUS_STATUS_REPLY,
        	"date"			=> time(),
            "privacy"		=> wsestStatus::WSEST_STATUS_PRIVACY_PRIVATE,
        	"message"		=> $message
        );
        $reply->setState($params);
	    self::save($reply);

	    wsestPersonManager::updateLastUpdate($sender_id);

	    //Update of the discussion ID
        $discussion_id = $reply->getState("id");
        $params["discussion_id"] = $discussion_id;
        $reply->setState($params);
        $s->update($reply);

        //Apply the template for the mail
        $sender = wsestPersonManager::getFullUser($sender_id);
        $receiver = wsestPersonManager::getFullUser($receiver_id);

        $tpl = eZTemplate::factory();
        $tpl->setVariable("receiver", $receiver);
        $tpl->setVariable("sender", $sender);
        $tpl->setVariable("message", $message);
        $tpl->setVariable("discussion_id", $discussion_id);

        $mail_content = $tpl->fetch("design:mail/new_response.tpl");

        //Build the mail
        $ini = eZINI::instance("status.ini");

        $sender_data = array(
            "email"=> $ini->variable("MailSettings", "SenderEmail"),
            "name"=> $ini->variable("MailSettings", "SenderName")
        );

        $receiver_data = array(
            array(
                "email" => $receiver["ezuser"]->attribute("email"),
                "name" => $receiver["ezuser"]->contentobject()->name()
            )
        );

        $mail = new wsestMailManager( $sender_data, $receiver_data, ezi18n( "status/mail", "You have a new response" ), $mail_content );

        //Send it
        $mail->send();

        return $discussion_id;
	}


	/**
	 * Performs a search for the subject term.
	 *
	 * @param string $text
	 * @param string $limit
	 * @param string $offset
	 */
	public static function fetchByTerm($text, $limit, $offset)
	{
	    //eZDebug::writeDebug("$text $limit $offset", "status-status_manager-fetchBySubject");
	    $search_results = self::getInstance()->search_engine->search( $text, $limit, $offset );
        $result = wsestPersistentObject::convertToArray($search_results);

	    //eZDebug::writeDebug( $result );
        return array('result' => $result );
	}


}

?>