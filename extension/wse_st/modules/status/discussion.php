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

try
{
    $person = wsestPersonManager::getCurrentUser();

    if (!isset($Params["DiscussionID"]) or !is_numeric($Params["DiscussionID"]))
    {
        return $module->handleError(wsestError::WSE_STATUS_ERROR_DISCUSSION_NOT_FOUND, "status");
    }

    //If we come from messages, the discussion we want to see is necessarily private
    $privacy = wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC;
    if ($http->sessionVariable("LastAccessesURI") == "/status/messages")
    {
        $privacy = wsestStatus::WSEST_STATUS_PRIVACY_PRIVATE;
    }

    //eZDebug::writeDebug($privacy, "status-discussion-discussion");

    $discussion_id = (int) $Params["DiscussionID"];
    $discussion = wsestStatusManager::fetchDiscussion($discussion_id, $privacy);

    //eZDebug::writeDebug($discussion, "status-discussion-discussion");

    if (!count($discussion))
    {
        return $module->handleError(wsestError::WSE_STATUS_ERROR_DISCUSSION_NOT_FOUND, "status");
    }

    $actors = wsestStatusManager::fetchDiscussionActors($discussion_id);

    $tpl = eZTemplate::factory();
    $tpl->setVariable("person", $person);
    $tpl->setVariable("view_parameters", $Params["UserParameters"]);
    $tpl->setVariable("discussion_id", $discussion_id);
    $tpl->setVariable("status", $discussion);
    $tpl->setVariable("actors", $actors);

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:status/discussion.tpl' );
    $Result['path'] = array( array( 'text' => ezi18n( 'status', 'Status' ),
                                     'url' => false ),
                              array( 'text' => ezi18n( 'status/home', 'Home' ),
                                     'url' => "/status/home" ),
                              array( 'text' => ezi18n( 'status/discussion', 'Discussion' ),
                                     'url' => false ),
    );

}
catch (Exception $e)
{
    return $module->handleError(wsestError::WSE_STATUS_ERROR_UNKNOWN, "status");
}

?>