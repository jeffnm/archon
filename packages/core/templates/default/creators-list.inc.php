<?php
header('Content-Type: application/json');

isset($_ARCHON) or die();

//echo print_r($_ARCHON) ;
//echo print_r($_ARCHON->AdministrativeInterface);


// echo print_r($arrCountries);


$session= $_SERVER['HTTP_SESSION'];
if ($_ARCHON->Security->Session->verifysession($session)){

    if ($_REQUEST['batch_start']){

            //Handles the zero condition
            $start = ( $_REQUEST['batch_start'] < 1 ? 1: $_REQUEST['batch_start']);

            $arrCreatorsrelated = getrelatedcreators();

            // pulls Batches of 100 across

           // $arrCreators =array_slice($_ARCHON->getAllCreators(),$start-1,100);
            $arrCreators = $_ARCHON->getAllCreators();

            foreach ($arrCreatorsrelated as $creatrel)
             {
                 $arrcreaterel = array($creatrel['RelatedCreatorID']=> $creatrel['CreatorRelationshipTypeID']);
                $arrCreators[$creatrel['CreatorID']]->CreatorRelationships[] = $arrcreaterel;

            }
            echo json_encode(array_slice($arrCreators,$start-1,100));








        }else{
            echo "batch_start Not found! Please enter a batch_start and resubmit the request.";
        }


} else {
        echo "Please submit your admin credentials to p=core/authenticate";
}

function getrelatedcreators()
{
    global $_ARCHON;


        $query = "SELECT CreatorID,RelatedCreatorID,CreatorRelationshipTypeID FROM tblCreators_CreatorCreatorIndex";
        $result = $_ARCHON->mdb2->query($query);


        if(PEAR::isError($result))
        {
            trigger_error($result->getMessage(), E_USER_ERROR);
        }

        while($row = $result->fetchRow())
        {
            $arrCreatorsrelated [] = $row;

        }

        $result->free();
        $result->free();
        return $arrCreatorsrelated;



}

?>
