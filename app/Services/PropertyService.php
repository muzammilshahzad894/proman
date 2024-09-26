<?php

namespace App\Services;

use App\Models\Property;

class PropertyService
{
    /**
     * Fetch properties based on search criteria.
     *
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPropertiesBySearch($search = null, $type = null)
    {
        $query = Property::where('status', 1)->orderBy('display_order', 'ASC');

        $query = match ($type) {
            'vacationRental' => $query->where('is_vacation', 1),
            'longTerm' => $query->where('is_long_term', 1),
            default => $query
        };
        
        if($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        return $query->paginate(config('pagination.per_page') ?? 10);
    }
}
