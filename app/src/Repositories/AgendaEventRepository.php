<?php

namespace SOPADevelopment\TwoSolar\Repositories;

use \PDO;
use \PDOException;

use SOPADevelopment\TwoSolar\Models\Event;

class AgendaEventRepository
{
	private $database;

	public function __construct($database)
	{
		$this->database = $database;
	}

	public function findByDate(string $date): array
	{
		// Make sure correct date format.
		$events = [];

		try {
			// Setup query
			$query = "SELECT
				*
			FROM
				agenda_events
			WHERE
				agenda_events.starts_at >= :date_starts_at
			AND
				agenda_events.ends_at <= :date_ends_at";

			// Handle the query
			$statement = $this->database->prepare($query);

			$statement->bindParam(':date_starts_at', date('Y-m-d 00:00:00', strtotime($date)));
			$statement->bindParam(':date_ends_at', date('Y-m-d 23:59:59', strtotime($date)));
			$statement->execute();

			$data = $statement->fetchAll(PDO::FETCH_OBJ);
			$statement = null;

			// Create the events
			if ($data) {
				foreach ($data as $item) {
					$event = new Event;

					$event->setID((int) $item->ID);
					$event->setAgendaID((int) $item->agendaID);
					$event->setUserID((int) $item->userID);
					$event->setTitle($item->title);
					$event->setStartsAt($item->starts_at);
					$event->setEndsAt($item->ends_at);

					$events[] = $event;
				}
			}
		} catch (PDOException $e) {
			exit('Something went wrong while getting the events.<br/> Error: '.$e->getMessage());
		}

		return $events;
	}
}
