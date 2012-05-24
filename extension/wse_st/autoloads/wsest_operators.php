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
 */class wsestStatusOperators
{
    /**
     * Return an array with the template operator name.
     *
     * @return array
     */
    public function operatorList()
    {
        return array( 'parse_status', 'extract_persons', 'array_intersect', 'strtotime' );
    }

    /**
     * Return true to tell the template engine that the parameter list exists per operator type,
     * this is needed for operator classes that have multiple operators.
     *
     * @return bool
     */
    function namedParameterPerOperator()
    {
        return true;
    }

    /**
     * Returns an array of named parameters, this allows for easier retrieval
     * of operator parameters. This also requires the function modify() has an extra
     * parameter called $namedParameters.
     *
     * @return array
     */
    public function namedParameterList()
    {
        return array(
        	"parse_status"=>array(),
        	"extract_persons"=>array(
        		"message" => array(
        			'type' => 'array',
					'required' => true,
					'default' => array()
        		),
        		"owner" => array(
        			'type' => 'string',
					'required' => true,
					'default' => ""
        		)
        	),
        	"array_intersect"=>array(
        	    "array1"=>array(
        	        "type" => "array",
        	        "required" => true,
        	        "default" => array()
        	    ),
        	    "array2"=>array(
        	        "type" => "array",
        	        "required" => true,
        	        "default"
        	    )
        	),

        	"strtotime"=>array(
        	    "string"=>array(
        	        "type" => "string",
        	        "required" => true,
        	        "default" => ""
        	    ),
        	    "timestamp"=>array(
        	        "type" => "string",
        	        "required" => false,
        	        "default" => 0
        	    )
        	),


        );
    }

    /**
     * Executes the PHP function for the operator cleanup and modifies $operatorValue.
     *
     * @param eZTemplate $tpl
     * @param string $operatorName
     * @param array $operatorParameters
     * @param string $rootNamespace
     * @param string $currentNamespace
     * @param mixed $operatorValue
     * @param array $namedParameters
     */
    public function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        switch ( $operatorName )
        {
            case 'parse_status':
            {
            	$operator = new eZTemplateStringOperator();
            	$operatorValue = $operator->wash($operatorValue, $tpl, "xhtml");
                $operatorValue = wsestStatusManager::parse($operatorValue);
            } break;
            case 'extract_persons':
            {
            	$operatorValue = wsestStatusManager::extractPersons($namedParameters["message"], $namedParameters["owner"] );
            }break;
            case 'array_intersect':
            {
                $operatorValue = array_intersect($namedParameters["array1"], $namedParameters["array2"]);
            }break;
            case 'strtotime':
            {
                if ( isset($namedParameters["timestamp"]) )
                {
                    $timestamp = floatval($namedParameters["timestamp"]);
                    $operatorValue = strtotime( str_replace("-", "/", $namedParameters["string"]), $timestamp );
                }
                else
                {
                    $operatorValue = strtotime( str_replace("-", "/", $namedParameters["string"]) );
                }
            }break;

        }

    }
}

?>