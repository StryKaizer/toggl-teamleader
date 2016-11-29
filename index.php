<?php
require 'vendor/autoload.php';
require 'config.php';

define('TOGGL_REPORTS_API_URL', 'https://toggl.com/reports/api/v2/');
define('TEAMLEADER_API_URL', 'https://app.teamleader.eu/api/');


use GuzzleHttp\Client;

$config = new \Doctrine\DBAL\Configuration();
$connectionParams = array(
  'url' => DB_CONNECTION,
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);


//$d = '2016-11-25T12:35:57+01:00';
//print(strtotime($d) + 3600);
//
//die();

$toggl_items = fetch_toggl_items();
foreach ($toggl_items as $item) {
  if (!is_already_imported($item->id)) {

    echo "Import: $item->description<br>";

    create_teamleader_timetracking($item);

    $conn->insert('mapping', array(
      'toggl_id' => $item->id,
      'toggl_project_id' => $item->pid,
      'description' => $item->description
    ));

  }
  else {
    echo "Already imported: $item->description<br>";
  }
}
print_r($result);


function fetch_toggl_items() {
  $toggl_client = new Client([
    'base_uri' => TOGGL_REPORTS_API_URL,
    'timeout' => 2.0,
  ]);
  $query = [];
  $query['user_agent'] = TOGGL_REPORTS_API_USER_AGENT;
  $query['workspace_id'] = TOGGL_REPORTS_API_WORKSPACE_ID;
  $query['project_ids'] = TOGGL_REPORTS_API_PROJECT_ID;

  $response = $toggl_client->request('GET', 'details', [
    'query' => $query,
    'auth' => [
      TOGGL_REPORTS_API_TOKEN,
      'api_token'
    ]
  ]);

  $result = json_decode($response->getBody());
  return $result->data;
}

function create_teamleader_timetracking($toggl_item) {

// Post to teamleader
  $teamleader_client = new Client([
    'base_uri' => TEAMLEADER_API_URL,
    'timeout' => 2.0,
  ]);

  $form_params = [];
  $form_params['api_group'] = TEAMLEADER_API_GROUP;
  $form_params['api_secret'] = TEAMLEADER_API_SECRET;

  $form_params['worker_id'] = TEAMLEADER_API_WORKER_ID;
  $form_params['task_type_id'] = TEAMLEADER_API_TASK_TYPE_ID;

  $form_params['description'] = $toggl_item->description;
  $form_params['start_date'] = 1480453833;
  $form_params['end_date'] = 1480458833;

  $form_params['invoiceable'] = 1;
  $form_params['for'] = 'company';
  $form_params['for_id'] = TEAMLEADER_API_COMPANY_ID;

  $response = $teamleader_client->request('POST', 'addTimetracking.php', ['form_params' => $form_params]);

  $body = $response->getBody();
  echo $body;
}


function is_already_imported($toggl_id) {
  global $conn;

  $sql = "SELECT * FROM mapping WHERE toggl_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(1, $toggl_id);
  $stmt->execute();

  return $stmt->rowCount() <> 0;
}