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
$Module = array( 'name' => ezi18n( 'status', 'Wascou Software Edition - Status' ) );

$ViewList = array();

$ViewList['ajax'] = array(
                   'functions' => array('private'),
                   'script' => 'ajax.php',
                   'params' => array( 'Action'),
);
$ViewList['favorites'] = array(
                   'functions' => array('public'),
                   'script' => 'favorites.php',
                   'params' => array( 'Name'),
);
$ViewList['home'] = array(
                   'functions' => array('private'),
                   'script' => 'home.php',
                   'params' => array(),
);
$ViewList['mine'] = array(
                   'functions' => array('public'),
                   'script' => 'mine.php',
                   'params' => array(),
);
$ViewList['profile'] = array(
                   'functions' => array('private'),
                   'script' => 'profile.php',
                   'params' => array( 'Name'),
);
$ViewList['followers'] = array(
                   'functions' => array('public'),
                   'script' => 'followers.php',
                   'params' => array( 'Name'),
);
$ViewList['leaders'] = array(
                   'functions' => array('public'),
                   'script' => 'leaders.php',
                   'params' => array( 'Name'),
);
$ViewList['discussion'] = array(
                   'functions' => array('public'),
                   'script' => 'discussion.php',
                   'params' => array( 'DiscussionID'),
);
$ViewList['messages'] = array(
                   'functions' => array('public'),
                   'script' => 'messages.php',
                   'params' => array(),
);
$ViewList['help'] = array(
                   'functions' => array('public'),
                   'script' => 'help.php',
                   'params' => array(),
);
$ViewList['search'] = array(
                   'functions' => array('public'),
                   'script' => 'search.php',
                   'params' => array( 'subject' ),
);

$FunctionList = array();
$FunctionList['public'] = array();
$FunctionList['private'] = array();

?>