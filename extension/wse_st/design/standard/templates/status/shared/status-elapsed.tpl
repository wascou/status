<div class="status-time-ago">
    {def $elapsed_time=currentdate()|sub($s.date)}
    	{if $elapsed_time|lt(5)}
    		{'Now'|i18n('design/status/shared/status-elapsed')}
    	{elseif and(
    		$elapsed_time|gt(5),
    		$elapsed_time|lt(60)
    	)}
    		{$elapsed_time|datetime('custom', '%s')|int()} {'seconds ago'|i18n('design/status/shared/status-elapsed')}
    	{elseif and(
    		$elapsed_time|lt(3600),
    		$elapsed_time|gt(60)
    	)}
    		{$elapsed_time|datetime('custom', '%i')|int()} {'minutes ago'|i18n('design/status/shared/status-elapsed')}
    	{elseif and(
    		$elapsed_time|gt(3600),
    		$elapsed_time|lt(86400)
    	)}
    		{$elapsed_time|datetime('custom', '%G')|int()} {'hours ago'|i18n('design/status/shared/status-elapsed')}
    	{elseif and(
    		$elapsed_time|gt(86400),
    		$elapsed_time|lt(604800)
    	)}
    		{$elapsed_time|datetime('custom', '%j')|int()} {'days ago'|i18n('design/status/shared/status-elapsed')}
    	{elseif $elapsed_time|gt(604800)}
    		{$elapsed_time|datetime('custom', '%W')|int()} {'weeks ago'|i18n('design/status/shared/status-elapsed')}
    	{/if}
    {undef $elapsed_time}
</div>