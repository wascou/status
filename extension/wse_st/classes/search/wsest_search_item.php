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
class wsestSearchItem extends wsestPersistentObject implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    //Status data
    public $id;
    public $person_id;
    public $via_id;
    public $discussion_id;
    public $status;
    public $message;
    public $date;
    public $privacy;
    public $subjects;

    //Person owner data
    public $owner_ezuser_id;
    public $owner_name;
    public $owner_login;
    public $owner_image_url;

    //Person via data
    public $via_ezuser_id;
    public $via_name;
    public $via_login;

    /**
     * Constructor :
     * @param string $id
     * @param string $person_id
     * @param string $via_id
     * @param string $discussion_id
     * @param string $status
     * @param string $date
     * @param string $message
     * @param string $privacy
     * @param string $subjects
     * @param string $owner_ezuser_id
     * @param string $owner_login
     * @param string $owner_name
     * @param string $owner_image_url
     * @param string $via_ezuser_id
     * @param string $via_name
     * @param string $via_login
     */
    public function __construct(
        $id=null,
        $person_id=null,
        $via_id=null,
        $discussion_id=null,
        $status=null,
        $date=null,
        $message=null,
        $privacy=null,
        $subjects=null,
        $owner_ezuser_id=null,
        $owner_login=null,
        $owner_name=null,
        $owner_image_url=null,
        $via_ezuser_id=null,
        $via_name=null,
        $via_login=null)
    {
        $this->id=$id;
        $this->person_id=$person_id;
        $this->via_id=$via_id;
        $this->discussion_id=$discussion_id;
        $this->status=$status;
        $this->date=$date;
        $this->message=$message;
        $this->privacy=$privacy;
        $this->subjects=$subjects;

        $this->owner_ezuser_id=$owner_ezuser_id;
        $this->owner_login=$owner_login;
        $this->owner_name=$owner_name;
        $this->owner_image_url=$owner_image_url;

        $this->via_ezuser_id=$via_ezuser_id;
        $this->via_login=$via_login;
        $this->via_name=$via_name;
    }

	/**
     * Return the definition for the search engine
     */
    public static function getDefinition()
    {
        $n = new ezcSearchDocumentDefinition( __CLASS__ );

        //Status
        $n->idProperty = 'id';
        $n->fields['id']        = new ezcSearchDefinitionDocumentField( 'id', ezcSearchDocumentDefinition::INT );
        $n->fields['person_id']   = new ezcSearchDefinitionDocumentField( 'person_id', ezcSearchDocumentDefinition::INT );
        $n->fields['via_id']   = new ezcSearchDefinitionDocumentField( 'via_id', ezcSearchDocumentDefinition::INT );
        $n->fields['discussion_id']   = new ezcSearchDefinitionDocumentField( 'discussion_id', ezcSearchDocumentDefinition::INT );
        $n->fields['status']   = new ezcSearchDefinitionDocumentField( 'status', ezcSearchDocumentDefinition::STRING );
        $n->fields['message']   = new ezcSearchDefinitionDocumentField( 'message', ezcSearchDocumentDefinition::TEXT);
        $n->fields['date']      = new ezcSearchDefinitionDocumentField( 'date', ezcSearchDocumentDefinition::DATE );
        $n->fields['privacy']   = new ezcSearchDefinitionDocumentField( 'privacy', ezcSearchDocumentDefinition::INT );
        $n->fields['subjects']   = new ezcSearchDefinitionDocumentField( 'subjects', ezcSearchDocumentDefinition::TEXT );

        //Owner
        $n->fields['owner_ezuser_id']   = new ezcSearchDefinitionDocumentField( 'owner_ezuser_id', ezcSearchDocumentDefinition::INT );
        $n->fields['owner_name']   = new ezcSearchDefinitionDocumentField( 'owner_name', ezcSearchDocumentDefinition::STRING );
        $n->fields['owner_login']   = new ezcSearchDefinitionDocumentField( 'owner_login', ezcSearchDocumentDefinition::STRING );
        $n->fields['owner_image_url']   = new ezcSearchDefinitionDocumentField( 'owner_image_url', ezcSearchDocumentDefinition::STRING );

        //Via
        $n->fields['via_ezuser_id']   = new ezcSearchDefinitionDocumentField( 'via_ezuser_id', ezcSearchDocumentDefinition::INT );
        $n->fields['via_name']   = new ezcSearchDefinitionDocumentField( 'via_name', ezcSearchDocumentDefinition::STRING );
        $n->fields['via_login']   = new ezcSearchDefinitionDocumentField( 'via_login', ezcSearchDocumentDefinition::STRING );

        return $n;
    }

    /**
     * Helper to fill the object from a status object.
     *
     * @param wsestStatus $status
     *
     * @return wsestSearchItem
     */
    public static function embed($status)
    {
        $item = new self();
        $status_state = $status->getState();

        $subjects = wsestStatusManager::extractSubjects($status_state["message"]);
        foreach ($subjects as $k=>$s)
        {
            $subjects[$k]=substr($s, 1, (strlen($s) - 1 ) );
        }
        $status_state["subjects"]=implode(" ",$subjects);

        if (!$status_state["discussion_id"])
        {
            $status_state["discussion_id"]=0;
        }

        if ( $status_state["via_id"] )
        {
            $via = wsestPersonManager::getFullUser( $status_state["via_id"] );
            $status_state["via_ezuser_id"]=$via["ezuser_id"];
            $status_state["via_login"]=$via["ezuser"]->attribute("login");

            $contentobject = eZContentObject::fetch( $via["ezuser"]->attribute( 'contentobject_id' ) );
            $status_state["via_name"]=$contentobject->attribute("name");

            unset($via);
            unset($contentobject);
        }
        else
        {
            $status_state["via_id"]=0;
            $status_state["via_ezuser_id"]=0;
            $status_state["via_name"]="";
            $status_state["via_login"]="";
        }

        if ( $status_state["person_id"] )
        {
            $owner = wsestPersonManager::getFullUser( $status_state["person_id"] );

            $status_state["owner_ezuser_id"]=$owner["ezuser_id"];
            $status_state["owner_login"]=$owner["ezuser"]->attribute("login");

            $contentobject = eZContentObject::fetch( $owner["ezuser"]->attribute( 'contentobject_id' ) );
            $status_state["owner_name"]=$contentobject->attribute("name");

            $data_map = $contentobject->dataMap();
            if ( isset($data_map["image"]) and $data_map["image"]->content() instanceof eZImageAliasHandler )
            {
                $image_data = $data_map["image"]->content()->imageAlias('status_avatar_small');
                $status_state["owner_image_url"]=$image_data["url"];
            }
            else
            {
                $status_state["owner_image_url"]="";
            }

            unset($contentobject);
            unset($owner);
            unset($data_map);
            unset($image_data);
        }

        $item->setState($status_state);

        return $item;
    }

}
?>