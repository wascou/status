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
 */$FunctionList = array();

$FunctionList['status'] = array( 'name' => 'status',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetch' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => false,
																 'default' => "" ),
                                                        array ( 'name' => 'scope',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => "all" ),
                                                        array ( 'name' => 'limit',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
                                                        array ( 'name' => 'offset',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
                                     ) );

$FunctionList['status_count'] = array( 'name' => 'status_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetchCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => false,
																 'default' => "" ),
                                                        array ( 'name' => 'scope',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => "all" ),
                                     ) );





/*$FunctionList['list'] = array( 'name' => 'list',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_list_manager.php',
                                                         'class' => 'wsestListManager',
                                                         'method' => 'fetch' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'id',
                                                                 'type' => 'integer',
                                                                 'required' => true ) ) );
*/



$FunctionList['favorites'] = array( 'name' => 'favorites',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetchFavorites' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'limit',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
                                                         array ( 'name' => 'offset',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
));
$FunctionList['favorites_count'] = array( 'name' => 'favorites_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetchFavoritesCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ) ) );

$FunctionList['followers'] = array( 'name' => 'followers',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchFollowers' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'limit',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
                                                         array ( 'name' => 'offset',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
));

$FunctionList['followers_count'] = array( 'name' => 'followers_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchFollowersCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ) ) );

$FunctionList['leaders_count'] = array( 'name' => 'leaders_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchLeadersCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ) ) );

$FunctionList['leaders'] = array( 'name' => 'leaders',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchLeaders' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'limit',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
                                                         array ( 'name' => 'offset',
                                                                 'type' => 'string',
                                                                 'required' => false,
                                                        		 'default' => false ),
));

$FunctionList['is_follower'] = array( 'name' => 'is_follower',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'isFollower' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'follower_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'leader_id',
                                                                 'type' => 'string',
                                                                 'required' => true )
                                                        )
);

$FunctionList['is_followed'] = array( 'name' => 'is_followed',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'isFollowed' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'leader_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'follower_id',
                                                                 'type' => 'string',
                                                                 'required' => true )
                                                        )
);

$FunctionList['mutually_following'] = array( 'name' => 'mutually_following',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchMutuallyFollowing' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true )
                                                         )
);
$FunctionList['mutually_following_count'] = array( 'name' => 'mutually_following_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'fetchMutuallyFollowingCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true )
                                                         )
);
$FunctionList['is_mutual_follower'] = array( 'name' => 'is_mutual_follower',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_person_manager.php',
                                                         'class' => 'wsestPersonManager',
                                                         'method' => 'isMutuallyFollowing' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'person_id2',
                                                                 'type' => 'string',
                                                                 'required' => true )
                                                         )
);

$FunctionList['search'] = array( 'name' => 'search',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetchByTerm' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array (
                                                         array ( 'name' => 'text',
                                                                 'type' => 'string',
                                                                 'required' => true ),
                                                         array ( 'name' => 'limit',
                                                                 'type' => 'string',
                                                                 'required' => false
                                                         ),
                                                         array ( 'name' => 'offset',
                                                                 'type' => 'string',
                                                                 'required' => false
                                                         ),
));

$FunctionList['messages_count'] = array( 'name' => 'messages_count',
                                 'call_method' => array( 'include_file' => 'extension/wse_st/classes/business/wsest_status_manager.php',
                                                         'class' => 'wsestStatusManager',
                                                         'method' => 'fetchMessagesCount' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array ( array ( 'name' => 'person_id',
                                                                 'type' => 'string',
                                                                 'required' => true ) ) );


?>