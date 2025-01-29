<?php

namespace App\Http\Controllers;

class ServiceProvidedController extends Controller
{
    public function getStoreServices($storeId = null)
    {
        $mockData = [
            [
                "id"          => 1,
                "odoo_id"     => 5,
                "name"        => "INVERSIONES FACOL 2020 C.A",
                "street"      => "AV NORTE-SUR L-D LOCAL NUMERO CIVICO NRO 68-40",
                "street2"     => "URB PARQUE COMERCIO INDUSTRIAL CASTILLITO",
                "city"        => "Valencia",
                "state"       => "Carabobo",
                "country"     => "Venezuela",
                "phone"       => "+58 241-8642654",
                "sequence_id" => 43351,
                "latitude"    => 10.123456,
                "longitude"   => -10.123456,
                "photo_url"   => "https://media.cnn.com/api/v1/images/stellar/prod/2023-11-24t123529z-673764545-rc2oj4ax8vjh-rtrmadp-3-usa-holidayshopping-blackfriday-activities.JPG?q=w_1110,c_fill",
                "photo_path"  => "/images/stores/media_cnn_photo1.jpg",
                "services"    => [
                    [
                        "id"          => 101,
                        "name"        => "Cambio de Aceite",
                        "short_name"  => "Aceite",
                        "description" => "Servicio de cambio de aceite para vehículos.",
                        "icon"        => "https://example.com/icons/oil_change.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                    [
                        "id"          => 102,
                        "name"        => "Alineación y Balanceo",
                        "short_name"  => "Alineación",
                        "description" => "Servicio completo de alineación y balanceo de neumáticos.",
                        "icon"        => "https://example.com/icons/alignment.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                ]
            ],
            [
                "id"          => 2,
                "odoo_id"     => 9,
                "name"        => "TECNI RUEDAS DE VALENCIA C.A.",
                "street"      => "AV BOLIVAR NORTE LOCAL GALPON NRO 139-124",
                "street2"     => "URB EL VINEDO",
                "city"        => "Valencia",
                "state"       => "Carabobo",
                "country"     => "Venezuela",
                "phone"       => "+58 241-8212231",
                "sequence_id" => 42609,
                "latitude"    => 20.654321,
                "longitude"   => -20.654321,
                "photo_url"   => "https://corporate.target.com/getmedia/d2441ab3-7b0b-4bff-9a6f-15df4690559d/New-Stores_Header_Target.png?width=620",
                "photo_path"  => "/images/stores/corporate_shutterstock_photo2.jpg",
                "services"    => [
                    [
                        "id"          => 201,
                        "name"        => "Cambio de Frenos",
                        "short_name"  => "Frenos",
                        "description" => "Revisión y cambio de frenos para vehículos.",
                        "icon"        => "https://example.com/icons/brake_change.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                    [
                        "id"          => 202,
                        "name"        => "Revisión de Batería",
                        "short_name"  => "Batería",
                        "description" => "Revisión y reemplazo de baterías.",
                        "icon"        => "https://example.com/icons/battery_check.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                ]
            ],
            [
                "id"          => 3,
                "odoo_id"     => 18,
                "name"        => "NEUMATICOS CARABOBO, C.A.",
                "street"      => "AV 100 LOCAL NUMERO CIVICO NRO 125-220 SECTOR AVENIDA BOLIVAR NORTE VALENCIA CARABOBO ZONA POSTAL 2001",
                "street2"     => "AVENIDA BOLIVAR NORTE",
                "city"        => "Valencia",
                "state"       => "Carabobo",
                "country"     => "Venezuela",
                "phone"       => "+58 241-3000050",
                "sequence_id" => 43353,
                "latitude"    => 30.987654,
                "longitude"   => -30.987654,
                "photo_url"   => "https://www.shutterstock.com/image-photo/jan-9-2020-mountain-view-600nw-1643463436.jpg",
                "photo_path"  => "/images/stores/shutterstock_photo3.jpg",
                "services"    => [
                    [
                        "id"          => 301,
                        "name"        => "Lavado de Autos",
                        "short_name"  => "Lavado",
                        "description" => "Lavado exterior e interior del vehículo.",
                        "icon"        => "https://example.com/icons/car_wash.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                    [
                        "id"          => 302,
                        "name"        => "Pulido de Carrocería",
                        "short_name"  => "Pulido",
                        "description" => "Servicio de pulido y encerado de la carrocería.",
                        "icon"        => "https://example.com/icons/car_polish.png",
                        "created_at"  => "2024-01-01",
                        "updated_at"  => null,
                        "deleted_at"  => null,
                    ],
                ]
            ]
        ];

        if ($storeId) {
            $filteredData = array_filter($mockData, function ($store) use ($storeId) {
                return $store['id'] == $storeId;
            });
        } else {
            $filteredData = $mockData;
        }

        return response()->json(array_values($filteredData), 200);
    }
}
