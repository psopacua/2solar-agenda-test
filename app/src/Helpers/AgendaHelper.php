<?php

namespace SOPADevelopment\TwoSolar\Helpers;

use SOPAdevelopment\TwoSolar\Models\Event;

class AgendaHelper
{
	private $dayStartTime = '07:00:00';
	private $dayEndTime = '17:00:00';

	public function generateWithAvailableSpots(array $events): ?array
	{
		$eventsByStartTime = [];
		$previousEndTime = null;
		$eventsCount = 0;
		$eventsTotal = count($events);

		foreach ($events as $event) {
			// Preconfigure dates
			$eventStartsAtTimestamp = strtotime($event->getStartsAt());
			$dayStartsAt = date('Y-m-d '.$this->dayStartTime, $eventStartsAtTimestamp);

			// Check if spot is available through the day.
			$availableEventStartsAtTimestamp = strtotime($dayStartsAt);
			$availableEventEndsAtTimestamp = strtotime('+2 hours 30 minutes', ($previousEndTime ?? $availableEventStartsAtTimestamp));

			if ($availableEventEndsAtTimestamp <= $eventStartsAtTimestamp) {
	
				$availableEventStartsAtTimestamp = (is_null($previousEndTime) === false)
					? strtotime('+30 minutes', $previousEndTime)
					: $availableEventStartsAtTimestamp;

				$eventsByStartTime[$availableEventStartsAtTimestamp] = $this->generateEvent(
					1,
					1,
					$availableEventStartsAtTimestamp,
					strtotime('-2 hours -30 minutes', $eventStartsAtTimestamp)
				);
			}

			// Set event specific data.
			$event->setState('main_element');
			$event->setIcon('glyphicon-flag');

			$eventsByStartTime[$eventStartsAtTimestamp] = $event;
			$previousEndTime = strtotime($event->getEndsAt());

			$eventsCount++;

			// Add schedule block if there is still time left 30 minutes after the last event.
			// New schedule block shall be added if end time is after day end time.
			$dayEndTimestamp = strtotime(date('Y-m-d '.$this->dayEndTime, $previousEndTime));
			$availableEventStartsAtTimestamp = strtotime('+30 minutes', $previousEndTime);
			if (
				$eventsCount === count($events)
				&& $availableEventStartsAtTimestamp < $dayEndTimestamp
			) {
				$eventsByStartTime[$availableEventStartsAtTimestamp] = $this->generateEvent(
					1,
					1,
					$availableEventStartsAtTimestamp,
					$dayEndTimestamp
				);	
			}
		}

		ksort($eventsByStartTime);

		return $eventsByStartTime;
	}

	public function generateJSON(array $data): string
	{
		$json = [];

		foreach ($data as $item) {
			$json[] = $item->toArray();
		}

		return json_encode($json);
	}

	private function generateEvent($agendaID, $userID, $startsAt, $endsAt): Event
	{
		$event = new Event;

		$event->setAgendaID($agendaID);
		$event->setUserID($userID);
		$event->setTitle('Nieuw evenement inplannen tussen '.date('H:i', $startsAt).' en '.date('H:i', $endsAt));
		$event->setStartsAt(date('Y-m-d H:i:s', $startsAt));
		$event->setEndsAt(date('Y-m-d H:i:s', $endsAt));

		return $event;
	}
}
