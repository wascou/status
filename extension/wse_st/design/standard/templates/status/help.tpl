<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
	<div class="class-article" id="status-home">

	    <div id="block-info" class="ui-corner-all">
	    	<h3>{'Table of contents'|i18n('design/status/help')}</h3>
	    	<ul>
	    		<li><a href="#concepts">{'Concepts'|i18n('design/status/help')}</a></li>
	    		<li><a href="#howto">{'How to do ... ?'|i18n('design/status/help')}</a>
	    			<ul>
	    				<li><a href="#status">{'Status'|i18n('design/status/help')}</a></li>
	    				<li><a href="#persons">{'Persons'|i18n('design/status/help')}</a></li>
	    			</ul>
	    		</li>
	    	</ul>

	    </div>

	    <div id="status-container">

	    	<h1><img src="{'status-logo.png'|ezimage('no')}" alt="{'WSE - Status'|i18n('design/status')}" title="{'WSE - Status'|i18n('design/status')}"/> {'Help'|i18n('design/status/discussion')}</h1>

	    	{if is_set($back_url)}
	    		<div class="back_url"><a href="{$back_url}">{'Back'|i18n('design/status/help')}</a></div>
	    	{/if}

			<a name="concepts"></a>
			<h2>{'Concepts'|i18n('design/status/help')}</h2>

			<div>
				<p>{'Your profile allow you to connect to other people using a one way concept : you can '|i18n('design/status/help')}<b>{'follow'|i18n('design/status/help')}</b> {'people.'|i18n('design/status/help')}</p>
				<p>{'The rules are the following :'|i18n('design/status/help')}</p>
				<ul>
					<li>{'You can follow whoever you want.'|i18n('design/status/help')}</li>
					<li>{'You can be followed by whoever.'|i18n('design/status/help')}</li>
					<li>{'If you are following someone and this person follow you, then you are mutuals followers.'|i18n('design/status/help')}</li>
				</ul>

				<img src="{'help-concepts.png'|ezimage('no')}" alt="{'Concepts'|i18n('design/status/help')}" title="{'Concepts'|i18n('design/status/help')}"/>

				<p>{'Now that your are connected to people, you can send a <b>status</b> : a short message with a specified length.'|i18n('design/status/help')}</p>
				<p>{'The rules are the following :'|i18n('design/status/help')}</p>
				<ul>
					<li>{'A status have some special characters :'|i18n('design/status/help')}
						<ul>
							<li><b>@</b> {'is used to designed people, for example <b>@john</b>.'|i18n('design/status/help')}</li>
							<li><b>#</b> {'is used to designed subject, for example <b>#revolution</b>.'|i18n('design/status/help')}</li>
						</ul>
					</li>
					<li>{'All special characters in a status are analysed and links are automatically added to the person or subject.'|i18n('design/status/help')}</li>
					<li>{'All the status you write are shared with all you followers.'|i18n('design/status/help')}</li>
					<li>{'You receive the shared status from the people you follow.'|i18n('design/status/help')}</li>
					<li>{'All the shared status are public.'|i18n('design/status/help')}</li>
					<li>{'If you and another person are mutual followers, you can send a private message to the person. It will not be shared with other people, only with the desired person.'|i18n('design/status/help')}</li>
					<li>{'A status can be added to your favorite : it is easier to recover it later.'|i18n('design/status/help')}</li>
					<li>{'A status can be spread. This allows you to send to your followers a status from one person you follow. By this, the original status is preserved and your name is added in the via field.'|i18n('design/status/help')}</li>
				</ul>

			</div>

			<a name="howto"></a>
			<h2>{'How to do ... ?'|i18n('design/status/help')}</h2>
			<div class="faq">
				<p>{'Here are some links to features described above :'|i18n('design/status/help')}</p>
				<a name="status"></a>
				<h3>{'Status'|i18n('design/status/help')}</h3>
				<ul>
					<li class="question">{'I want to write a status.'|i18n('design/status/help')}</li>
					<li class="answer">{'Just go to your profile and type something in the text field in top of your status.'|i18n('design/status/help')}</li>

					<li class="question">{'I want to find a subject.'|i18n('design/status/help')}</li>
					<li class="answer">{'Use the small search box inside the person information box at the right of the screen.'|i18n('design/status/help')}</li>


					<li class="question">{'I want to spread a status.'|i18n('design/status/help')}</li>
					<li class="answer">{'When you hover a status, a small toolbar appear.'|i18n('design/status/help')} {'You just have to push the spread button.'|i18n('design/status/help')}</li>


					<li class="question">{'I want to add a status to my favorites page.'|i18n('design/status/help')}</li>
					<li class="answer">{'When you hover a status, a small toolbar appear.'|i18n('design/status/help')} {'You just have to push the favorite button.'|i18n('design/status/help')}</li>


					<li class="question">{'I want to remove a status.'|i18n('design/status/help')}</li>
					<li class="answer">{'When you hover a status, a small toolbar appear.'|i18n('design/status/help')} {'You just have to push the remove button.'|i18n('design/status/help')}</li>


					<li class="question">{'I want to unspread a status.'|i18n('design/status/help')}</li>
					<li class="answer">{'When you hover an already spread status, the spread icon is colored. You just have to push again the spread button to unspread the status.'|i18n('design/status/help')}</li>


					<li class="question">{'I want to send a direct message to someone.'|i18n('design/status/help')}</li>
					<li class="answer">{'This will only work if you mutually follow someone. Then, you can do this by two ways :'|i18n('design/status/help')}
						<ul>
							<li>{'You can go to the profile of the person and push the message button.'|i18n('design/status/help')}</li>
							<li>{'On your profile, you can click on the number of messages and reach the messages page. There you can push the New message button.'|i18n('design/status/help')}</li>
						</ul>
					</li>
				</ul>
				<a name="persons"></a>
				<h3>{'Persons'|i18n('design/status/help')}</h3>
				<ul>
					<li class="question">{'I want to find someone.'|i18n('design/status/help')}</li>
					<li class="answer">{'Try the search :'|i18n('design/status/help')} <a href="{'content/advancedsearch'|ezurl('no')}">{'Search'|i18n('design/status/help')}</a> </li>

					<li class="question">{'I want to follow someone.'|i18n('design/status/help')}</li>
					<li class="answer">{'Just go on the profile of the person and push the Follow button located inside its information box.'|i18n('design/status/help')}</li>

					<li class="question">{'I want to unfollow someone.'|i18n('design/status/help')}</li>
					<li class="answer">{'Just go on the profile of the person and push the Unfollow button located inside its information box.'|i18n('design/status/help')}</li>

				</ul>

				{if is_set($back_url)}
    	    		<div class="back_url"><a href="{$back_url}">{'Back'|i18n('design/status/help')}</a></div>
    	    	{/if}

			</div>

	    </div>

	</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>