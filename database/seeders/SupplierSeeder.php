<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'TechCorp Electronics',
                'code' => 'TECH001',
                'email' => 'orders@techcorp.com',
                'phone' => '+1-555-0101',
                'address' => '123 Tech Street, Silicon Valley, CA 94025',
                'contact_person' => 'John Smith',
                'contact_phone' => '+1-555-0102',
                'contact_email' => 'john.smith@techcorp.com',
                'lead_time_days' => 7,
                'minimum_order_amount' => 500.00,
                'payment_terms' => 'Net 30',
                'is_active' => true,
                'notes' => 'Primary electronics supplier',
            ],
            [
                'name' => 'Fashion Forward Inc.',
                'code' => 'FASH001',
                'email' => 'orders@fashionforward.com',
                'phone' => '+1-555-0201',
                'address' => '456 Fashion Ave, New York, NY 10001',
                'contact_person' => 'Sarah Johnson',
                'contact_phone' => '+1-555-0202',
                'contact_email' => 'sarah.johnson@fashionforward.com',
                'lead_time_days' => 14,
                'minimum_order_amount' => 1000.00,
                'payment_terms' => 'Net 45',
                'is_active' => true,
                'notes' => 'Premium clothing supplier',
            ],
            [
                'name' => 'Home & Garden Supply Co.',
                'code' => 'HOME001',
                'email' => 'orders@homegarden.com',
                'phone' => '+1-555-0301',
                'address' => '789 Garden Lane, Portland, OR 97201',
                'contact_person' => 'Mike Wilson',
                'contact_phone' => '+1-555-0302',
                'contact_email' => 'mike.wilson@homegarden.com',
                'lead_time_days' => 10,
                'minimum_order_amount' => 300.00,
                'payment_terms' => 'Net 30',
                'is_active' => true,
                'notes' => 'Home improvement supplies',
            ],
            [
                'name' => 'Sports Equipment Pro',
                'code' => 'SPORT001',
                'email' => 'orders@sportsequipment.com',
                'phone' => '+1-555-0401',
                'address' => '321 Sports Blvd, Denver, CO 80201',
                'contact_person' => 'Lisa Davis',
                'contact_phone' => '+1-555-0402',
                'contact_email' => 'lisa.davis@sportsequipment.com',
                'lead_time_days' => 5,
                'minimum_order_amount' => 200.00,
                'payment_terms' => 'Net 30',
                'is_active' => true,
                'notes' => 'Sports and outdoor equipment',
            ],
            [
                'name' => 'BookWorld Publishers',
                'code' => 'BOOK001',
                'email' => 'orders@bookworld.com',
                'phone' => '+1-555-0501',
                'address' => '654 Library Street, Boston, MA 02101',
                'contact_person' => 'David Brown',
                'contact_phone' => '+1-555-0502',
                'contact_email' => 'david.brown@bookworld.com',
                'lead_time_days' => 21,
                'minimum_order_amount' => 150.00,
                'payment_terms' => 'Net 60',
                'is_active' => true,
                'notes' => 'Books and media supplier',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
