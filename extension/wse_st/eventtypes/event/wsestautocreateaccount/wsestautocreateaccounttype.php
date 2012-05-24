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

class wsestAutoCreateAccountType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "wsestautocreateaccount";

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct( self::WORKFLOW_TYPE_STRING, ezi18n( 'status', "Auto create an account for WSE Status" ) );
    }

    /**
     * Handle the event
     * (non-PHPdoc)
     * @see kernel/classes/eZWorkflowType::execute()
     * @reimp
     */
    public function execute( $process, $event )
    {
        $parameters = $process->attribute( 'parameter_list' );

        $object = eZContentObject::fetch($parameters["object_id"]);
        $ini = eZINI::instance("status.ini");
        $classes = $ini->variable("WorkflowSettings", "UserClasses");

        if (!in_array($object->attribute("class_identifier"), $classes))
        {
        	return eZWorkflowType::STATUS_ACCEPTED;
        }

        $data_map = $object->dataMap();

        if ($data_map['user_account']->hasContent())
        {
        	$content = $data_map["user_account"]->content();
        	$user_id = $content->attribute("contentobject_id");

        	include_once("extension/wse_st/classes/config.php");
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

                wsestPersonManager::index($person);
	        }
	        //Else if we have only one status account, we update it
	        elseif (count( $persons ) == 1 )
	        {
                $person = wsestPersonManager::fetchFromID( $content->attribute('login'), true );
                wsestPersonManager::index($person);
	        }
	        elseif (count($persons) > 1)
	        {
	        	eZDebug::writeError("WSE Status - More than one status account for the user '$user_id'.");
	        }
        }

        return eZWorkflowType::STATUS_ACCEPTED;
    }



}
eZWorkflowEventType::registerEventType( wsestAutoCreateAccountType::WORKFLOW_TYPE_STRING, 'wsestautocreateaccounttype' );


?>