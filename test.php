<?php
echo "start";
// SOAP_CLIENT_BASEDIR - folder that contains the PHP Toolkit and your WSDL
// $USERNAME - variable that contains your Salesforce.com username (must be in the form of an email)
$USERNAME = "***";
$PASSWORD = "***";
// $PASSWORD - variable that contains your Salesforce.com password
define("SOAP_CLIENT_BASEDIR", "PHPToolkit/soapclient");

require_once (SOAP_CLIENT_BASEDIR.'/SforcePartnerClient.php');


try {
  ini_set("soap.wsdl_cache_enabled", "0"); 
  $mySforceConnection = new SforcePartnerClient();
  $mySoapClient = $mySforceConnection->createConnection(SOAP_CLIENT_BASEDIR.'/partner.wsdl.xml');
  $mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);

//$query = "SELECT Id, FirstName, LastName, Phone from Contact";
  $query = "SELECT  ID,Date_Reported__c,Title__c FROM Product_Experience_Report__c";
  $response = $mySforceConnection->query($query);
  $queryResult = new QueryResult($response);
  foreach ($queryResult->records as $record) {
    print_r($record);
    echo "<br>";
    echo $record->Product_Experience_Report__c->Id[0];
    echo "<br>";

  }

} catch (Exception $ex) {
  print_r($ex);
  echo $mySforceConnection->getLastRequest();
  print_r($ex);

}

?>
