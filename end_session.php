<?php
session_start();//must start session to destroy it
session_unset();//remove all session variables
session_destroy(); //destroy the session
echo "end session";
exit();
