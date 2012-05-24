<div id="person-picture" class="ui-corner-all box">
    <div class="searchbox status-subject-searchbox">
    	<form action="{'status/search'|ezurl('no')}" method="get">
    		<h2>{'Find it !'|i18n('design/status/shared/person-info', 'Title')}</h2>
    		<div>
    			<p><b>{'Some rules to follow to find what you want :'|i18n('design/statusshared/person-info/', 'Static')}</b></p>
    			<p>{'Subject : # prior the term'|i18n('design/statusshared/person-info/', 'Static')}</p>
    			<p>{'Person : @ prior the term'|i18n('design/statusshared/person-info/', 'Static')}</p>
    			<p>{'Full text : just the term'|i18n('design/statusshared/person-info/', 'Static')}</p>
    		</div>

    		<input type="text" id="SearchSubjectText" name="SearchSubjectText" class="text ui-corner-all" value="{'Subject to find'|i18n('design/status/shared/person-info', 'Field default value')}"/>
    		<input type="submit" id="SearchSubjectButton" class="button" name="SearchSubjectButton" value="{'OK'|i18n('design/status/shared/person-info','Button')}"/>
    	</form>
    </div>
</div>