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
    public function getPropertiesBySearch($search = null)
    {
        $query = Property::where('status', 1)->orderBy('display_order', 'ASC');

        if ($search) {
            switch ($search) {
                case 'vacationRental':
                    $query->where('is_vacation', 1);
                    break;
                case 'longTerm':
                    $query->where('is_long_term', 1);
                    break;
                default:
                    $query->where('title', 'like', '%' . $search . '%');
                    break;
            }
        }

        return $query->get();
    }
}
