<?php
#include_once 'config.php';
#include_once 'A/Email.php';
#include_once 'A/Email/Multipart.php';

include_once '../../A/autoload.php';

$email = new A_Email();
$attachment = new A_Email_Multipart();
$email->setReplyto('replyto@email.com');

$emailtext = 'TITLE
This is a text email.';
$emailhtml = '<html>
<body>
<h1>TITLE</h1>
This is a <b>HTML</b> email.
</body>
</html>';
$attachment->addPart($emailtext, "text/plain;\n\tcharset=\"ISO-8859-1\"");
$attachment->addPart($emailhtml, "text/html;\n\tcharset=\"ISO-8859-1\"");
$body = $attachment->getMessage();
$email->addHeaders($attachment->getHeaders('multipart/alternative'));
$errmsg = $email->send('from@email.com', 'to@email.com', 'Example Email', $body);

echo "Error=$errmsg<br/>";
