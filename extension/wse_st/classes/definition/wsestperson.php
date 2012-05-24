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
$def = new ezcPersistentObjectDefinition();
$def->table = "wsest_person";
$def->class = "wsestPerson";

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentNativeGenerator' );

$def->properties['ezuser_id'] = new ezcPersistentObjectProperty;
$def->properties['ezuser_id']->columnName = 'ezuser_id';
$def->properties['ezuser_id']->propertyName = 'ezuser_id';
$def->properties['ezuser_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['last_update'] = new ezcPersistentObjectProperty;
$def->properties['last_update']->columnName = 'last_update';
$def->properties['last_update']->propertyName = 'last_update';
$def->properties['last_update']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


//Relations with wsestStatus
$relations = new ezcPersistentRelationCollection();

//Relation - Favorites
$relations["favorites"] = new ezcPersistentManyToManyRelation(
    "wsest_person",
    "wsest_status",
    "wsest_favorite"
);
$relations["favorites"]->columnMap = array(
    new ezcPersistentDoubleTableMap( "id", "person_id", "status_id", "id" )
);

//Person - Status - Owner
$relations["owners"] = new ezcPersistentOneToManyRelation(
    "wsest_person",
    "wsest_status"
);
$relations["owners"]->columnMap = array(
    new ezcPersistentSingleTableMap( "id" , "person_id" )
);
$relations["owners"]->cascade = true;

//Person - Status - Receiver
$relations["receiver"] = new ezcPersistentOneToManyRelation(
    "wsest_person",
    "wsest_status"
);
$relations["receiver"]->columnMap = array(
    new ezcPersistentSingleTableMap( "id" , "receiver_id" )
);

$def->relations["wsestStatus"] = $relations;



//Relations with wsestPerson
$relations = new ezcPersistentRelationCollection();

//Relation - Followers / Leaders
$relations["followers"] = new ezcPersistentManyToManyRelation(
    "wsest_person",
    "wsest_person",
    "wsest_followers"
);
$relations["followers"]->columnMap = array(
    new ezcPersistentDoubleTableMap( "id", "follower_id", "leader_id", "id" ),
);

$relations["leaders"] = new ezcPersistentManyToManyRelation(
    "wsest_person",
    "wsest_person",
    "wsest_followers"
);
$relations["leaders"]->columnMap = array(
    new ezcPersistentDoubleTableMap( "id", "follower_id", "leader_id", "id" ),
);
$def->relations["wsestPerson"] = $relations;


//Relation with wsestList
$relations = new ezcPersistentRelationCollection();

//Person - List - Owner
$relations["owner"] = new ezcPersistentOneToManyRelation(
    "wsest_person",
    "wsest_list"
);
$relations["owner"]->columnMap = array(
    new ezcPersistentSingleTableMap( "id" , "owner_id" )
);
$relations["owner"]->cascade = true;

//Person - List - Listed people
$relations["listed"] = new ezcPersistentManyToManyRelation(
    "wsest_person",
    "wsest_list",
    "wsest_list_item"
);
$relations["listed"]->columnMap = array(
    new ezcPersistentDoubleTableMap( "id", "person_id", "list_id", "id" ),
);


$def->relations["wsestList"] = $relations;

return $def;

?>