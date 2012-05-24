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
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Wascou Software Edition - Status\n" .
                                                        "Update the index of status elements\n" .
                                                        "\n" ),
                                     'use-session' => true,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );
//$script->setUseDebugOutput( true );
$script->startup();
$script->initialize();

include_once("extension/wse_st/classes/config.php");

$limit = 100;
$offset = 0;

$persons_count = wsestPersonManager::getPersonsCount();
$cycle_count =  round($persons_count / $limit );

$cli->output("Number of persons : $persons_count");

$output = new ezcConsoleOutput();
$output->formats->bar->color = 'blue';
$output->formats->bar->style = array( 'bold' );
$options = array(
    'emptyChar'       => ' ',
    'barChar'         => '-',
    'formatString'    => '[' . $output->formatText( '%bar%', 'bar' ) . '] %fraction%% - %act% / %max%',
    'redrawFrequency' => 1,
);


$bar = new ezcConsoleProgressbar( $output, (int) $persons_count, $options );

for ($i = 0; $i < $cycle_count ; $i++ )
{
    $offset = $limit * $i;
    $persons = wsestPersonManager::getPersons($offset, $limit);

    $persons_keys = array_keys($persons);
    $last_person = $persons_keys[ count($persons) - 1 ];

    foreach ($persons as $k => $person)
    {
        $commit = false;
        if ( $k == $last_person )
        {
            $commit = true;
        }

        wsestPersonManager::index($person, $commit);
        $bar->advance();
        //print(" - $offset/$limit == $k / $last_person / $commit");
    }

    unset($persons_keys);
    unset($last_person);

    unset($persons);

}

$bar->finish();
$output->outputLine();

$cli->output("Done.");

$script->shutdown();
?>