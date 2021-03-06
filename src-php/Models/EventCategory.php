<?php

namespace Dewsign\NovaEvents\Models;

use Dewsign\NovaEvents\Models\Event;
use Maxfactor\Support\Webpage\Model;
use Maxfactor\Support\Webpage\Traits\HasSlug;
use Maxfactor\Support\Model\Traits\HasActiveState;
use Maxfactor\Support\Webpage\Traits\HasMetaAttributes;

class EventCategory extends Model
{
    use HasSlug;
    use HasActiveState;
    use HasMetaAttributes;

    protected $table = 'nova_event_categories';

    protected $metaDefaults = [
        'browser_title' => 'title',
        'h1' => 'title',
        'nav_title' => 'title',
    ];

    protected $appends = [
        'name',
    ];

    /**
     * For meta attributes and repeaters that look for a name field (Hyperlink blocks)
     *
     * @return String
     */
    public function getNameAttribute()
    {
        return $this->title;
    }

    public function events()
    {
        return $this->belongsToMany(config('nova-events.models.event', Event::class), 'nova_event_categories_nova_events', 'nova_event_category_id', 'nova_event_id');
    }

    public function getActiveEventsWithDatesAttribute()
    {
        return $this->events()->upcomingAndOnGoing()->withComputedDates()->active()->orderBy('start_date')->get();
    }

    /**
     * Add required items to the breadcrumb seed
     *
     * @return array
     */
    public function seeds()
    {
        return array_merge(parent::seeds(), [
            [
                'name' => __('Events'),
                'url' => route('events.index'),
            ],
            [
                'name' => $this->navTitle,
                'url' => route('events.list', [$this]),
            ],
        ]);
    }
}
