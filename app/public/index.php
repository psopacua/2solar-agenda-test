<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Pre configurations.
date_default_timezone_set("Europe/Helsinki");

$date = ($_GET['date'] ?? date('d-m-Y')) . ' 00:00:00';

// Setup Database Connection
try {
	$database = new PDO(
		'mysql:host=mysql_mysql_1;dbname=2solar',
		'root',
		'krijgjeniet');
} catch(Exception $e) {
	exit('Something went wrong while establishing a connection with the database!<br/> Error: '.$e->getMessage());
}

// Get all the events by date.
$eventRepository = new SOPADevelopment\TwoSolar\Repositories\AgendaEventRepository($database);
$events = $eventRepository->findByDate($date);

// Generate the available spots.
$agendaHelper = new SOPADevelopment\TwoSolar\Helpers\AgendaHelper;
$eventsAll = $agendaHelper->generateWithAvailableSpots($events);
$eventsJSON = $agendaHelper->generateJSON($eventsAll);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>2Solar Agenda Test</title>
  		<link rel="stylesheet" href="styles.css" />
  		<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.15.0/lodash.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.js"></script>
	</head>
	<body>
		<div id="app">
		    <div class="container">
		        <div class="page-header">
		            <span id="timeline-header">Agenda - <?= $date ?></span>
		        </div>
		        <timeline v-bind:items='<?= $eventsJSON ?>'></timeline>
		    </div>

		    <template id="timeline-template">
		        <ul class="timeline">
		            <li 
		                v-for="item in items" 
		                is="timeline-item" 
		                :item="item">
		            </li>
		        </ul>
		    </template>

		    <template id="timeline-item-template">
		        <li class="timeline-item" v-bind:class="{ pulse_wrap: item.wrap_mode }">

		            <div class="timeline-badge {{ item.icon_state }}"><i class="glyphicon {{ item.icon }}"></i></div>
		                <div class="timeline-panel {{ item.state }}">
		                    <div class="timeline-heading">
		                        <h4 class="timeline-title">{{ item.title }}</h4>
		                        <div class="timeline-panel-controls">
		                            <div class="timestamp" v-if="item.id">
		                                <small class="">{{ item.starts_at }}</small>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="timeline-footer" v-if="item.id">
		                            <div class="timestamp">
		                                <small class="">{{ item.ends_at }}</small>
		                            </div>
		                    </div>
		                </div>
		            </div>
		        </li>
		    </template>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.26/vue.js"></script>
		<script src="script.js"></script>
	</body>
</html>