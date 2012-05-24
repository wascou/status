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
class wsestSearchHandler
{

    /**
     * Search session
     * @var ezcSearchSession
     */
    private $session;

    /**
     * Create a new Search handler and set the SolR handler
     */
    public function __construct()
    {
        //Does the handler exist ?
        if ( !( $this->session instanceof ezcSearchSession ) )
        {
            $ezfind_ini = eZINI::instance("ezfind.ini");
            if ( $ezfind_ini->variable("LanguageSearch","MultiCore") == "enabled" )
            {
                $solr_ini = eZINI::instance("solr.ini");
                $status_ini = eZINI::instance("status.ini");

                $solr_base = $solr_ini->variable("SolrBase", "SearchServerURI");
                $location = $status_ini->variable("SolRSettings", "WseStatusCore");

                $data = array();
                preg_match("#([^:]*)://([^:]*):([^/]*)(/.*)#", $solr_base, $data);
                $protocol     = $data[1];
                $domain       = $data[2];
                $port         = $data[3];
                $uri          = $data[4];

                //eZDebug::writeDebug($data, "status-search-handler");

                $this->session = new ezcSearchSession(
                    new ezcSearchSolrHandler($domain, $port , $uri."/".$location),
                    new ezcSearchEmbeddedManager()
                );

                $this->session->beginTransaction();
            }
            else
            {
                eZDebug::writeError("To WSE Status, eZFind's SolR instance must be configured as a multicore instance.", "wsest-solr-search-handler");
                return;
            }
        }

        if ( !( $this->session instanceof ezcSearchSession ) )
        {
            eZDebug::writeError("Unable to get the search session.", "wsest-solr-search-handler");
            return;
        }
    }


    /**
     * Index the provided object
     * @param wsestSearchItem $object
     */
    public function index($object)
    {
        $this->session->index($object);
    }

    /**
     * Commit the changes
     *
     */
    public function commit()
    {
        $this->session->handler->commit();
    }


    /**
     * Performs the search for the text.
     * Takes the following rules :
     * - if the term has an \@, search in the "owner_login" field
     * - else if the term has a #, search in the "subjects" field
     * - else search in the "message" field
     *
     * @param string $text
     * @param string $limit
     * @param string $offset
     */
    public function search($text, $limit, $offset)
    {
        if ( strstr( $text, '@' ) )
        {
            $text = preg_replace('~@~', '', $text);

            $q = $this->session->createFindQuery( 'wsestSearchPerson' );
            $q->where(
                $q->eq( "login", $text )
            );
        }
        else
        {
            if ( strstr( $text , '#' ) )
            {
                $field = "subjects";
                $text = preg_replace('~#~', '', $text);
            }
            else
            {
                $field = "message";
            }

            eZDebug::writeDebug("$text in $field", "status-search_handler-search");

            $q = $this->session->createFindQuery( 'wsestSearchItem' );
            $q->where(
                $q->lAnd(
                    $q->eq( $field, $text ),
                    $q->eq( 'privacy', wsestStatus::WSEST_STATUS_PRIVACY_PUBLIC )
                )
            );
            $q->orderBy( 'date' , ezcSearchQueryTools::DESC );
        }

        $q->limit( $limit, $offset );

        eZDebug::writeDebug( $this->session->handler->getQuery($q) , "status-search_handler-search");

        $search_results = array();

        try
        {
            $search_results = $this->session->find( $q );
        }
        catch (Exception $e)
        {
            eZDebug::writeError($e->getMessage(),"status-search_handler-search");
        }

        //eZDebug::writeDebug($results, "status-search_handler-search");

        return $search_results;
    }
}
?>