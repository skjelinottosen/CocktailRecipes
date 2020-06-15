<?php

function OpenCon(){
    $host = "localhost";
    $user = "webuser";
    $password = "123";
    $database = "applicationDB";
    $conn = new mysqli($host, $user, $password, $database) or die("Connect failed: %s\n" . $conn->error);

    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}
