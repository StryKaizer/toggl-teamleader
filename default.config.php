<?php

define('TOGGL_REPORTS_API_TOKEN', 'your_api_token_here'); // TOGGL API TOKEN
define('TOGGL_REPORTS_API_USER_AGENT', 'your_email_here'); // Your toggl email
define('TOGGL_REPORTS_API_WORKSPACE_ID', 'your_toggl_workspace_id'); // ID of your workspace

define('TEAMLEADER_API_GROUP', 'your_teamleader_group'); // TEAMLEADER GROUP (found in API details)
define('TEAMLEADER_API_SECRET', 'your_teamleader_secret'); // TEAMLEADER SECRET (found in API details)
define('TEAMLEADER_API_TASK_TYPE_ID', 'your_teamleader_task_type_id'); // ID of the task type, e.g. 62379 for development
define('TEAMLEADER_API_WORKER_ID', 'your_worker_id'); // Teamleader User id who creates the task

define('DB_CONNECTION', 'mysql://username:password@localhost/toggl_teamleader'); // CONNECTION STRING LOCAL DB


// Map toggl project ids to teamleader company ids
// Key = toggl project id
// value = teamleader company id
$mapping = array(
  'your_toggl_project_id_here' => 'your_teamleader_company_id'
);
