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
class wsestSearchPerson extends wsestPersistentObject implements ezcBasePersistable, ezcSearchDefinitionProvider
{
    //Person data
    public $id;
    public $ezuser_id;
    public $name;
    public $login;
    public $image_url;
    public $bio;

    /**
     * Constructor :
     * @param string $id
     * @param string $ezuser_id
     * @param string $login
     * @param string $name
     * @param string $image_url
     * @param string $bio
     */
    public function __construct(
        $id=null,
        $ezuser_id=null,
        $login=null,
        $name=null,
        $image_url=null,
        $bio=null )
    {
        $this->id=$id;
        $this->ezuser_id=$ezuser_id;
        $this->login=$login;
        $this->name=$name;
        $this->image_url=$image_url;
        $this->bio=$bio;
    }

	/**
     * Return the definition for the search engine
     */
    public static function getDefinition()
    {
        $n = new ezcSearchDocumentDefinition( __CLASS__ );

        //Owner
        $n->idProperty = 'id';
        $n->fields['id']           = new ezcSearchDefinitionDocumentField( 'id', ezcSearchDocumentDefinition::INT );
        $n->fields['ezuser_id']    = new ezcSearchDefinitionDocumentField( 'ezuser_id', ezcSearchDocumentDefinition::INT );
        $n->fields['name']         = new ezcSearchDefinitionDocumentField( 'name', ezcSearchDocumentDefinition::STRING );
        $n->fields['login']        = new ezcSearchDefinitionDocumentField( 'login', ezcSearchDocumentDefinition::STRING );
        $n->fields['image_url']    = new ezcSearchDefinitionDocumentField( 'image_url', ezcSearchDocumentDefinition::STRING );
        $n->fields['bio']          = new ezcSearchDefinitionDocumentField( 'bio', ezcSearchDocumentDefinition::STRING );

        return $n;
    }

    /**
     * Helper to fill the object from a person object.
     *
     * @param wsestPerson $person
     *
     * @return wsestSearchPerson
     */
    public static function embed($person)
    {
        $item = new self();

        $person_object = $person->getState();

        $person_state["id"]=$person_object["id"];

        $person_state["ezuser_id"]=$person_object["ezuser_id"];
        $ezuser=eZUser::fetch($person_object["ezuser_id"]);
        $person_state["login"]=$ezuser->attribute("login");

        $contentobject = eZContentObject::fetch( $ezuser->attribute( 'contentobject_id' ) );
        $person_state["name"]=$contentobject->attribute("name");

        $data_map = $contentobject->dataMap();
        if ( isset($data_map["image"]) and $data_map["image"]->content() instanceof eZImageAliasHandler )
        {
            $image_data = $data_map["image"]->content()->imageAlias('status_avatar_small');
            $person_state["image_url"]=$image_data["url"];
        }
        else
        {
            $person_state["image_url"]="";
        }

        $ini = eZINI::instance('status.ini');
        $bio = $ini->variable("GlobalSettings", "BioAttribute");

        if ( isset($data_map[$bio]) and $data_map[$bio]->content() )
        {
            $person_state["bio"]=$data_map[$bio]->content();
        }

        unset($contentobject);
        unset($owner);
        unset($data_map);
        unset($image_data);

        $item->setState($person_state);

        return $item;
    }
}
?>