<?php
// session_start() has to go right at the top, before any output!
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>REST/OAuth Example</title>
    </head>
    <body>
        <tt>
<?php

    require_once ('PHPToolkit/soapclient/SforcePartnerClient.php');
    require_once ('PHPToolkit/soapclient/SforceEnterpriseClient.php');
    define("USERNAME", "***");
    define("PASSWORD", "***");
    define("SECURITY_TOKEN", "***");
    ini_set("soap.wsdl_cache_enabled", "0"); 


    try {
        echo "<table border=\"1\"><tr><td>";
        echo "First with the enterprise client<br/><br/>\n";
        $mySforceConnection = new SforceEnterpriseClient();
        $mySforceConnection->createConnection("PHPToolkit/soapclient/enterprise.wsdl.xml");
        // Simple example of session management - first call will do
        // login, refresh will use session ID and location cached in
        // PHP session
        if (isset($_SESSION['enterpriseSessionId'])) {
            $location = $_SESSION['enterpriseLocation'];
            $sessionId = $_SESSION['enterpriseSessionId'];
            $mySforceConnection->setEndpoint($location);
            $mySforceConnection->setSessionHeader($sessionId);
            echo "Used session ID for enterprise<br/><br/>\n";
        } else {
            $mySforceConnection->login(USERNAME, PASSWORD.SECURITY_TOKEN);
            $_SESSION['enterpriseLocation'] = $mySforceConnection->getLocation();
            $_SESSION['enterpriseSessionId'] = $mySforceConnection->getSessionId();
            echo "Logged in with enterprise<br/><br/>\n";
        }
        /*
        $query = "SELECT Id, FirstName, LastName, Phone from Contact";
        $response = $mySforceConnection->query($query);
        echo "Results of query '$query'<br/><br/>\n";
        foreach ($response->records as $record) {
            echo $record->Id.": ".$record->FirstName." "
           .$record->LastName." ".$record->Phone."<br/>\n";
        }
        */
        $query = "SELECT  ID,Date_Reported__c,Title__c,Account__r.Name,Patient__r.Name FROM Product_Experience_Report__c";
        $response = $mySforceConnection->query($query);
        echo "Results of query '$query'<br/><br/>\n";
        foreach ($response->records as $record) {
            echo "Id: ".$record->Id;
            echo "  Title: ".$record->Title__c;
            echo "  Center: ". $record->Account__r->Name; 
            echo "  Patient:  ". $record->Patient__r->Name;
            echo "  Date: ".$record->Date_Reported__c."<br/>\n";
           echo "<br>";
        }


       
        echo "</td></tr></table>";
    } catch (Exception $e) {
        echo "Exception ".$e->faultstring."<br/><br/>\n";
        echo "Last Request:<br/><br/>\n";
        echo $mySforceConnection->getLastRequestHeaders();
        echo "<br/><br/>\n";
        echo $mySforceConnection->getLastRequest();
        echo "<br/><br/>\n";
        echo "Last Response:<br/><br/>\n";
        echo $mySforceConnection->getLastResponseHeaders();
        echo "<br/><br/>\n";
        echo $mySforceConnection->getLastResponse();
    }



            ?>
        </tt>
    </body>
</html>