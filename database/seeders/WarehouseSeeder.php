<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Main Distribution Center',
                'code' => 'MDC',
                'address' => '1000 Industrial Blvd, Los Angeles, CA 90210',
                'phone' => '+1-555-1001',
                'email' => 'mdc@company.com',
                'manager_name' => 'Robert Anderson',
                'manager_phone' => '+1-555-1002',
                'manager_email' => 'robert.anderson@company.com',
                'is_active' => true,
                'notes' => 'Primary distribution center for all products',
            ],
            [
                'name' => 'East Coast Warehouse',
                'code' => 'ECW',
                'address' => '2000 Commerce Dr, New York, NY 10001',
                'phone' => '+1-555-2001',
                'email' => 'ecw@company.com',
                'manager_name' => 'Jennifer Martinez',
                'manager_phone' => '+1-555-2002',
                'manager_email' => 'jennifer.martinez@company.com',
                'is_active' => true,
                'notes' => 'East coast distribution center',
            ],
            [
                'name' => 'Midwest Hub',
                'code' => 'MWH',
                'address' => '3000 Logistics Way, Chicago, IL 60601',
                'phone' => '+1-555-3001',
                'email' => 'mwh@company.com',
                'manager_name' => 'Michael Thompson',
                'manager_phone' => '+1-555-3002',
                'manager_email' => 'michael.thompson@company.com',
                'is_active' => true,
                'notes' => 'Central distribution hub',
            ],
            [
                'name' => 'South Regional Center',
                'code' => 'SRC',
                'address' => '4000 Southern Ave, Houston, TX 77001',
                'phone' => '+1-555-4001',
                'email' => 'src@company.com',
                'manager_name' => 'Amanda Rodriguez',
                'manager_phone' => '+1-555-4002',
                'manager_email' => 'amanda.rodriguez@company.com',
                'is_active' => true,
                'notes' => 'Southern regional distribution',
            ],
            [
                'name' => 'West Coast Facility',
                'code' => 'WCF',
                'address' => '5000 Pacific St, Seattle, WA 98101',
                'phone' => '+1-555-5001',
                'email' => 'wcf@company.com',
                'manager_name' => 'Christopher Lee',
                'manager_phone' => '+1-555-5002',
                'manager_email' => 'christopher.lee@company.com',
                'is_active' => true,
                'notes' => 'Northwest distribution facility',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}
