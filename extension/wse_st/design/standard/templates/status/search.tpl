{def $search_text=''
   $status=array()
   $offset=0
   $limit = ezini("GlobalSettings", "SearchLimit", "status.ini")
}

{if is_set($view_parameters['offset'])}
	{set $offset=$view_parameters['offset']}
{/if}

{if ezhttp_hasvariable( 'SearchSubjectText', 'get' )}
	{set $search_text=ezhttp( 'SearchSubjectText' , 'get' )}
{elseif and( is_set( $view_parameters['subject'] ), $view_parameters['subject']|ne('') ) }
	{set $search_text=concat("#",$view_parameters['subject']) }
{/if}


{if $search_text|ne('')}
	{set $status=fetch('status', 'search', hash( 'text', $search_text, 'limit', $limit, 'offset', $offset ) )}
{/if}


<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
  <div class="class-article" id="status-home">

    <div id="block-info">
             {include uri="design:status/shared/person-info.tpl" person=$person logged_person=$person}
    </div>

      <div id="status-container">

      {if $search_text|ne('')}

            <h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/>
              {'Search for "%s" subject'|i18n( 'design/status/discussion', 'Dynamic', hash( '%s', $search_text ) )}
            </h1>

            <div class="searchbox status-subject-searchbox">
              <div>
                <p><b>{'Some rules to follow to find what you want :'|i18n('design/statusshared/person-info/', 'Static')}</b></p>
              <ul>
                  <li>{'Subject : # prior the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                  <li>{'Person : @ prior the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                  <li>{'Full text : just the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                </ul>
            </div>

              <form action="{'status/search'|ezurl('no')}" method="get">
                  <input type="text" id="SearchSubjectText" name="SearchSubjectText" class="text" value="{$search_text}"/>
                  <input type="submit" id="SearchSubjectButton" class="button" name="SearchSubjectButton" value="{'OK'|i18n('design/status/shared/person-info')}"/>
                </form>
            </div>


            <p>{'Search perfomed in %s ms and returning %results result(s).'|i18n( 'design/status/search', '', hash( '%s', $status.queryTime, '%results', $status.resultCount ) )}</p>
            <div>

              {foreach $status.documents as $id => $search_result}
              <div class="status-box">
                {if $search_text|begins_with('@')}
                  <div class="status-image">
                      {if $search_result.document.image_url|ne('')}<img src="/{$search_result.document.image_url}" />
                      {else}<img src="{'p-avatar-50.png'|ezimage('no')}" alt="{$search_result.document.login}"/>{/if}
                    </div>

                    <h2 class="status-header">
                      {$search_result.document.login} <span class="status-user-name"> - <a href="{concat('status/profile/', $search_result.document.login)|ezurl('no')}">{$search_result.document.name}</a></span>
                    </h2>

                  <div class="status-message">
                  {$search_result.document.bio}
                  </div>

                {else}
                    <div class="status-image">
                      {if $search_result.document.owner_image_url|ne('')}<img src="/{$search_result.document.owner_image_url}" />
                      {else}<img src="{'p-avatar-50.png'|ezimage('no')}" alt="{$search_result.document.owner_login}"/>{/if}
                    </div>

                    <h2 class="status-header">
                      {$search_result.document.owner_login} <span class="status-user-name"> - <a href="{concat('status/profile/', $search_result.document.owner_login)|ezurl('no')}">{$search_result.document.owner_name}</a></span>
                    </h2>
                    <div class="status-message">{$search_result.document.message|parse_status()}</div>

                    {include uri="design:status/shared/status-elapsed.tpl" s=hash('date', strtotime($search_result.document.date.date) )}
                  {/if}

              </div>

              {/foreach}

          {include uri='design:navigator/google.tpl'
                   page_uri="/status/search"
                   item_count=$status.resultCount
                   view_parameters=$view_parameters
                   item_limit=$limit
                   page_uri_suffix=concat("?SearchSubjectText=",$search_text,"&SearchSubjectButton=")
              }

            </div>
          {else}
            <h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/>
              {'Search for subjects'|i18n( 'design/status/discussion' )}
            </h1>

            <div class="searchbox status-subject-searchbox">
              <div>
                <p><b>{'Some rules to follow to find what you want :'|i18n('design/statusshared/person-info/', 'Static')}</b></p>
              <ul>
                  <li>{'Subject : # prior the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                  <li>{'Person : @ prior the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                  <li>{'Full text : just the term'|i18n('design/statusshared/person-info/', 'Static')}</li>
                </ul>
            </div>

              <form action="{'status/search'|ezurl('no')}" method="get">
                  <input type="text" id="SearchSubjectText" name="SearchSubjectText" class="text" value="{'Subject to find'|i18n('design/status/shared/person-info')}"/>
                  <input type="submit" id="SearchSubjectButton" class="button" name="SearchSubjectButton" value="{'OK'|i18n('design/status/shared/person-info')}"/>
                </form>
            </div>


          {/if}

      </div>

  </div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
{undef $status $search_text}
