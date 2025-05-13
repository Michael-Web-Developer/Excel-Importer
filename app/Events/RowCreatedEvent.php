<?php

namespace App\Events;

use App\Models\Row;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RowCreatedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(private readonly Row $row)
	{
	}

	public function broadcastOn(): array
	{
		return [
			new PrivateChannel('row-listener')
		];
	}
}
