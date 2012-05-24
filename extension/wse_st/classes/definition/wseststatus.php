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
$def->table = "wsest_status";
$def->class = "wsestStatus";

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentNativeGenerator' );

$def->properties['status'] = new ezcPersistentObjectProperty;
$def->properties['status']->columnName = 'status';
$def->properties['status']->propertyName = 'status';
$def->properties['status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['date'] = new ezcPersistentObjectProperty;
$def->properties['date']->columnName = 'date';
$def->properties['date']->propertyName = 'date';
$def->properties['date']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['message'] = new ezcPersistentObjectProperty;
$def->properties['message']->columnName = 'message';
$def->properties['message']->propertyName = 'message';
$def->properties['message']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['privacy'] = new ezcPersistentObjectProperty;
$def->properties['privacy']->columnName = 'privacy';
$def->properties['privacy']->propertyName = 'privacy';
$def->properties['privacy']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['discussion_id'] = new ezcPersistentObjectProperty;
$def->properties['discussion_id']->columnName   = 'discussion_id';
$def->properties['discussion_id']->propertyName = 'discussion_id';
$def->properties['discussion_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

//Relation - Favorites
$def->relations["wsestPerson"] = new ezcPersistentManyToManyRelation(
    "wsest_status",
	"wsest_person",
    "wsest_favorite"
);
$def->relations["wsestPerson"]->columnMap = array(
    new ezcPersistentDoubleTableMap( "id", "status_id", "person_id", "id" )
);


//Relation - Person - Owner
$def->properties['person_id'] = new ezcPersistentObjectProperty;
$def->properties['person_id']->columnName   = 'person_id';
$def->properties['person_id']->propertyName = 'person_id';
$def->properties['person_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

//Relation - Person - Receiver
$def->properties['receiver_id'] = new ezcPersistentObjectProperty;
$def->properties['receiver_id']->columnName   = 'receiver_id';
$def->properties['receiver_id']->propertyName = 'receiver_id';
$def->properties['receiver_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;





//Relation - Status - Issued from
$def->properties['via_id'] = new ezcPersistentObjectProperty;
$def->properties['via_id']->columnName   = 'via_id';
$def->properties['via_id']->propertyName = 'via_id';
$def->properties['via_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$relations = new ezcPersistentRelationCollection();
$relations["via"] = new ezcPersistentOneToManyRelation(
    "wsest_status",
    "wsest_status"
);
$relations["via"]->columnMap = array(
    new ezcPersistentSingleTableMap( "id" , "via_id" )
);
$def->relations["wsestStatus"] = $relations;


return $def;
?>