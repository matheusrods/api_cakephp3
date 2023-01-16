<?php
    $serverName = "sqltst.local.buonny";
    $database = "RHHealth";
    $uid = 'sqlsystem';
    $pwd = 'buonny1818';
echo "php 7 sqlserver\n";
    try {
        $conn = new PDO(
            "sqlsrv:server=$serverName;Database=$database",
            $uid,
            $pwd,
            array(
                //PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );

 

        echo("conectou \n");
    }
    catch(PDOException $e) {
        die("Error connecting to SQL Server: " . $e->getMessage());
    }

 

 

?>
