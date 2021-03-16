<?php

declare(strict_types=1);

namespace SOPADevelopment\TwoSolar\Models;

class Event
{
	private $ID;
	private $agendaID;
	private $userID;
	private $title;
	private $state = '';
	private $icon = 'glyphicon-plus';
	private $iconState = 'planning';
	private $startsAt;
	private $endsAt;

	public function setID(int $ID): self
	{
		$this->ID = $ID;

		return $this;
	}

	public function getID(): ?int
	{
		return $this->ID;
	}

	public function setAgendaID(int $agendaID): self
	{
		$this->agendaID = $agendaID;

		return $this;
	}

	public function getAgendaID(): int
	{
		return $this->agendaID;
	}

	public function setUserID(int $userID): self
	{
		$this->userID = $userID;

		return $this;
	}

	public function getUserID(): int
	{
		return $this->userID;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setState(string $state): self
	{
		$this->state = $state;

		return $this;
	}

	public function getState(): string
	{
		return $this->state;
	}

	public function setIcon(string $icon): self
	{
		$this->icon = $icon;

		return $this;
	}
	public function getIcon(): string
	{
		return $this->icon;
	}

	public function setIconState(string $state): self
	{
		$this->iconState = $state;

		return $this;
	}

	public function getIconState(): string
	{
		return $this->iconState;
	}

	public function setStartsAt(string $startsAt): self
	{
		$this->startsAt = $startsAt;

		return $this;
	}

	public function getStartsAt(): string
	{
		return $this->startsAt;
	}

	public function setEndsAt(string $endsAt): self
	{
		$this->endsAt = $endsAt;

		return $this;
	}

	public function getEndsAt(): string
	{
		return $this->endsAt;
	}

	public function getWrapMode(): bool
	{
		$currentTimestamp = strtotime('now');

		return $currentTimestamp >= strtotime($this->getStartsAt())
			&& $currentTimestamp <= strtotime($this->getEndsAt());
	}

	public function toArray(): array
	{
		return [
			'id' => $this->getID(),
			'agenda_id' => $this->getAgendaID(),
			'user_id' => $this->getUserID(),
			'title'=> $this->getTitle(),
			'wrap_mode'=> $this->getWrapMode(),
			'state'=> $this->getState(),
			'icon' => $this->getIcon(),
			'icon_state' => $this->getIconState(),
			'starts_at' => date('H:i', strtotime($this->getStartsAt())),
			'ends_at'=> date('H:i', strtotime($this->getEndsAt()))
		];
	}
}
