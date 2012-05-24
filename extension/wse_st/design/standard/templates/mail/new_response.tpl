<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>
{'You have an answer'|i18n('design/status/mail')}
</title>
</head>
<body>

<h1>{$sender} {'has made the following answer'|i18n('design/status/mail')}</h1>

<p>{$message}</p>

{'Click here to learn more about'|i18n('design/status/mail')} <a href="{ezsys( 'hostname' )}/{concat('/status/profile/', $sender.login)|ezurl('no')}">{$sender.login}</a><br>
{'Click here to see all the discussion'|i18n('design/status/mail')} <a href="{ezsys( 'hostname' )}/{concat('/status/discussion/', $discussion_id)|ezurl('no')}">{'Full discussion'|i18n(design/status/mail)}</a></a>


</body>
</html>