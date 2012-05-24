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
include_once("extension/wse_st/classes/config.php");
$module = $Params["Module"];
$http = eZHTTPTool::instance();
$results = array(
	"success"   => false,
    "data"		=> null
);

$person = wsestPersonManager::getCurrentUser();

try
{

    switch ($Params["Action"])
    {
        case "follow":
            if ($http->hasVariable("PersonID"))
            {
                wsestPersonManager::addFollower($http->variable("PersonID"), $person['id']);
                $results["success"]=true;
            }

        break;
        case "unfollow":
            if ($http->hasVariable("PersonID"))
            {
                wsestPersonManager::removeFollower($http->variable("PersonID"), $person['id']);
                $results["success"]=true;
            }
        break;
        case "add_favorite":
            if ($http->hasVariable("StatusID") and $http->hasVariable("StatusOwnerID"))
            {
                wsestStatusManager::addFavorite($http->variable("StatusOwnerID"), $http->variable("StatusID"));
                $results["success"]=true;
            }
        break;
        case "remove_favorite":
            if ($http->hasVariable("StatusID") and $http->hasVariable("StatusOwnerID"))
            {
                wsestStatusManager::removeFavorite($http->variable("StatusOwnerID"), $http->variable("StatusID"));
                $results["success"]=true;
            }
        break;
        case "delete_status":
            if ($http->hasVariable("StatusID"))
            {
                wsestStatusManager::removeStatus($person['id'], $http->variable("StatusID"));
                $results["success"]=true;
            }
        break;
        case "spread":
            if ($http->hasVariable("StatusID"))
            {
                wsestStatusManager::spread($person['id'], $http->variable("StatusID"));
                $results["success"]=true;
            }
        break;
        case "unspread":
            if ($http->hasVariable("StatusID"))
            {
                wsestStatusManager::unspread($person['id'], $http->variable("StatusID"));
                $results["success"]=true;
            }
        break;
        case "reply":
        	if ($http->hasVariable("StatusID") and $http->hasVariable("Message"))
        	{
        		$discussion_id = wsestStatusManager::reply($person['id'], $http->variable("StatusID"), $http->variable("Message"));
        		$results["success"]=true;
        		//$results["data"]=array("discussion_id"=>$discussion_id);
        	}
        break;
        case "sendmessage":
        	if ($http->hasVariable("PersonID") and $http->hasVariable("Message"))
        	{
        		$discussion_id = wsestStatusManager::addMessage($person['id'], $http->variable("PersonID"), $http->variable("Message"));
        		$results["success"]=true;
        		//$results["data"]=array("discussion_id"=>$discussion_id);
        	}
        break;

        default:{}
        break;
    }
}
catch (Exception $e)
{
    $results["success"]=false;
    $ini = eZINI::instance("status.ini");
    if ($ini->variable("DebugSettings", "DebugAjax") == "enabled")
    {
        $results["error"]=$e->getMessage();
    }
}

print(json_encode($results));
eZExecution::cleanExit();
?>