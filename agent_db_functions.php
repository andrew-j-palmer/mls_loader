<?php
include "./db_creds.php";


$db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//ACTIVATE SCOOTER MODE (prevent int cast to string maybe)
$scootermode = $db->query("set session sql_mode = '';");
$scootermode->execute();
$superscootermode = $db->query("set session sql_mode = 'NO_ENGINE_SUBSTITUTION';");
$superscootermode->execute();
//SCOOTER MODE ENGAGED


function makeAgentIncremental($mls, $query, $field) {
    echo "running makeIncremental..\n";
    global $db;
    $lastrun = $db->prepare('select time from prev_runs where mlsname = ? and success = 1 order by time desc limit 1');
    $lastrun-> execute(array($mls));
    $result = $lastrun->fetch();
    //if there's no successful runs in db, we want full pull (new mls)
    //therefore, don't add incremental query
    if (isset($result['time'])) {
        echo "Adding Timestamp to existing query...\n";
        $timestamp = preg_replace("/ /", "T", $result['time']);
        $incremental = '('.$field.'='.$timestamp.'+),'.$query;
        return $incremental;
    } else {
        echo "no successful run, doing full pull\n";
        return $query;
    }
}

function checkAgent($mls, $agentID, $timestamp) {
    /* NEEDS TO:
    - see if an id exists for a listing (see if we have it yet)
    - if we DON'T have id, insert record
    - if we DO have id, update

    -IMPORTANT-
    need to RETURN ID if we have it
    */
    global $db;
    $query = $db->prepare("select Timestamp, ID from agentsimport where AgentID = ? and MLSName = ? limit 1");
    $query-> execute(array($agentID, $mls));
    $return = $query->fetchAll();
    $update = array_pop($return);
    //echo $timestamp." - ".$update['Timestamp']."\n";
    $state = array('action' => '', 'id' => '');
    if (isset($update['ID'])) {
        $state['ID'] = $update['ID'];
        if (strcmp($timestamp, $update['Timestamp']) !== 0) {
            $state['action'] = "update";
            return $state;
        } 
        else {
        //timestamps match, don't do nuthin'
        $state['action'] = "current";
        return $state;
        }
    }
    else {
        $state['action'] = "insert";
        return $state; 
    }

}

function insertAgent($listing) {
    global $db;
    $insert = $db->prepare("insert into agentsimport (inData,AgentID,AgentEmail,AgentFirstName,
    AgentFullName,AgentLastName,AgentPhone1,AgentPhone2,AgentUrl,MLSName,OfficeID,Timestamp
    ) values (:inData,:AgentID,:AgentEmail,:AgentFirstName,
    :AgentFullName,:AgentLastName,:AgentPhone1,:AgentPhone2,:AgentUrl,:MLSName,:OfficeID,:Timestamp)");
    $insert->execute($agent);
}

function updateAgent($agent, $id) {
    $listing['id'] = $id;
    //var_dump($listing); exit;
    global $db;
    //DEFINITELY 67 bound parameters
    $update = $db->prepare("update agentsimport set inData=:inData,AgentID=:AgentID,AgentEmail=:AgentEmail,AgentFirstName=:AgentFirstName,
    AgentFullName=:AgentFullName,AgentLastName=:AgentLastName,AgentPhone1=:AgentPhone1,AgentPhone2=:AgentPhone2,AgentUrl=:AgentUrl,MLSName=:MLSName,OfficeID=:OfficeID,Timestamp=:Timestamp 
    where id = :id");
    $update->execute($agent);

}

function agentsInData($mls, $agentids) {
    global $db;
    $markAll = $db->prepare("update agentsimport set inData = 1 where mlsname = ?");
    $markAll->execute(array($mls));
    $oldIDs = $db->prepare("select AgentID from listingsimport where mlsname = ?");
    $oldIDs->execute(array($mls));
    $compareIDs = $oldIDs->fetchAll();
    $dbIDs = array_column($compareIDs, 'AgentID');
    $dumps = array_diff($dbIDs, $agentids);
    foreach ($dumps as $dump) {
        echo "deleting $dump\n";
        $update = $db->prepare("update agentsimport set indata = 0 where mlsname = ? and AgentID = ?");
        $update->execute([$mls, $dump]);
    }
    echo count($dumps)." records marked for delete\n";
}

function deleteAgents($mls){
    global $db;
    //for incremental, we want to delete everything that hasn't been marked "in data"
    //for full pulls, wipe it all first because we're going to see every listing anyway
    //we don't have to worry about checking moddates, etc - that's what the importer's for
    $delQuery = $db->prepare('delete from agentsimport where MLSName = ? and inData = 0');
    $delQuery->execute([$mls]);
    $deleted = $delQuery->rowCount(); 
    return $deleted;
}

function resetAgents($mls) {
    echo "resetting agents back to unseen\n";
    global $db;
    $resetQuery = $db->prepare('update agentsimport set indata = 0 where mlsname = ?');
    $resetQuery->execute(array($mls));
}
?>