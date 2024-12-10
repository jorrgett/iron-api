<?php

namespace App\Repositories\UserList;

use App\Models\Service;
use App\Models\ServiceAlignmentComplete;
use App\Models\ServiceBalancingComplete;
use App\Models\ServiceBatteryComplete;
use App\Models\ServiceOilComplete;
use App\Models\ServiceRotationComplete;
use App\Models\ServiceTireComplete;

class UserListRepository
{
    public function filterServices(array $services)
    {
        $results = collect();

        foreach ($services as $service => $serviceFilters) {
            switch ($service) {
                case 'service_battery':
                    $results = $results->merge($this->filterServiceBattery($serviceFilters));
                    break;

                case 'service_oil':
                    $results = $results->merge($this->filterServiceOil($serviceFilters));
                    break;

                case 'service_balancing':
                    $results = $results->merge($this->filterServiceBalancing($serviceFilters));
                    break;

                case 'service_rotation':
                    $results = $results->merge($this->filterServiceRotation($serviceFilters));
                    break;

                case 'service_alignment':
                    $results = $results->merge($this->filterServiceAlignment($serviceFilters));
                    break;

                case 'service_tire':
                    $results = $results->merge($this->filterServiceTire($serviceFilters));
                    break;
            }
        }

        return $results;
    }

    private function filterServiceBattery(array $serviceFilters)
    {
        $query = ServiceBatteryComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['state'])) {
                $query->whereIn('health_status_final', $serviceFilters['state']);
            }

            if (isset($serviceFilters['brand'])) {
                $query->whereIn('battery_brand_id', $serviceFilters['brand']);
            }

            if (isset($serviceFilters['model'])) {
                $query->whereIn('battery_model_id', $serviceFilters['model']);
            }

            if (isset($serviceFilters['flaw']) && is_array($serviceFilters['flaw'])) {
                $query->where(function ($q) use ($serviceFilters) {
                    foreach ($serviceFilters['flaw'] as $flaw) {
                        switch ($flaw) {
                            case 'good_condition':
                                $q->orWhere('good_condition', true);
                                break;
                            case 'liquid_leakage':
                                $q->orWhere('liquid_leakage', true);
                                break;
                            case 'corroded_terminals':
                                $q->orWhere('corroded_terminals', true);
                                break;
                            case 'frayed_cables':
                                $q->orWhere('frayed_cables', true);
                                break;
                            case 'inflated':
                                $q->orWhere('inflated', true);
                                break;
                            case 'cracked_case':
                                $q->orWhere('cracked_case', true);
                                break;
                            case 'new_battery':
                                $q->orWhere('new_battery', true);
                                break;
                            case 'battery_charged':
                                $q->orWhere('battery_charged', true);
                                break;
                        }
                    }
                });
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }

    private function filterServiceOil(array $serviceFilters)
    {
        $query = ServiceOilComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['change_required'])) {
                $condition = $serviceFilters['change_required'] ? '<=' : '>';
                $query->whereColumn('life_span', $condition, 'kms_recorridos');
            }

            if (isset($serviceFilters['brand'])) {
                $query->whereIn('brand_id', $serviceFilters['brand']);
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }

    private function filterServiceBalancing(array $serviceFilters)
    {
        $query = ServiceBalancingComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['change_required'])) {
                $condition = $serviceFilters['change_required'] ? '>' : '<=';
                $query->where('kms_recorridos', $condition, 8000);
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }

    private function filterServiceRotation(array $serviceFilters)
    {
        $query = ServiceRotationComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['change_required'])) {
                $condition = $serviceFilters['change_required'] ? '>' : '<=';
                $query->where('kms_recorridos', $condition, 5000);
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }

    private function filterServiceAlignment(array $serviceFilters)
    {
        $query = ServiceAlignmentComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['change_required'])) {
                $condition = $serviceFilters['change_required'] ? '>' : '<=';
                $query->where('kms_recorridos', $condition, 10000);
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }

    private function filterServiceTire(array $serviceFilters)
    {
        $query = ServiceTireComplete::query();

        $service = $serviceFilters['with_service'] ?? true;

        if (count($serviceFilters) >= 1 && $service === true) {

            if (isset($serviceFilters['state']) && is_array($serviceFilters['state'])) {
                $query->where(function ($q) use ($serviceFilters) {
                    foreach ($serviceFilters['state'] as $state) {
                        switch ($state) {
                            case '25':
                                $q->orWhere('count_25', true);
                                break;
                            case '50':
                                $q->orWhere('count_50', true);
                                break;
                            case '75':
                                $q->orWhere('count_75', true);
                                break;
                            case '100':
                                $q->orWhere('count_100', true);
                                break;
                        }
                    }
                });
            }

            if (isset($serviceFilters['brand'])) {
                $query->whereIn('tire_brand_id', $serviceFilters['brand']);
            }

            if (isset($serviceFilters['model'])) {
                $query->whereIn('tire_model_id', $serviceFilters['model']);
            }

            if (isset($serviceFilters['flaw']) && is_array($serviceFilters['flaw'])) {
                $query->where(function ($q) use ($serviceFilters) {
                    foreach ($serviceFilters['flaw'] as $flaw) {
                        switch ($flaw) {
                            case 'regular':
                                $q->orWhere('regular', true);
                                break;
                            case 'staggered':
                                $q->orWhere('staggered', true);
                                break;
                            case 'central':
                                $q->orWhere('central', true);
                                break;
                            case 'right_shoulder':
                                $q->orWhere('right_shoulder', true);
                                break;
                            case 'left_shoulder':
                                $q->orWhere('left_shoulder', true);
                                break;
                            case 'not_apply':
                                $q->orWhere('not_apply', true);
                                break;
                            case 'bulge':
                                $q->orWhere('bulge', true);
                                break;
                            case 'perforations':
                                $q->orWhere('perforations', true);
                                break;
                            case 'vulcanized':
                                $q->orWhere('vulcanized', true);
                                break;
                            case 'aging':
                                $q->orWhere('aging', true);
                                break;
                            case 'cracked':
                                $q->orWhere('cracked', true);
                                break;
                            case 'deformations':
                                $q->orWhere('deformations', true);
                                break;
                            case 'separations':
                                $q->orWhere('separations', true);
                                break;
                            case 'tire_change':
                                $q->orWhere('tire_change', true);
                                break;
                        }
                    }
                });
            }

            $resPartners = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereIn('odoo_id', $resPartners)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        if (isset($serviceFilters) && count($serviceFilters) === 1 && $serviceFilters['with_service'] === false) {
            $resPartnersWithServices = $query->where('res_partner_id', '!=', null)->pluck('service_id');

            return Service::whereNotIn('odoo_id', $resPartnersWithServices)
                ->with(['drivers', 'vehicles'])
                ->whereHas('drivers')
                ->whereHas('vehicles')
                ->get();
        }

        return collect();
    }
}
