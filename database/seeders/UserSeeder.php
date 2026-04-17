<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $prefix = Carbon::now()->format('Ym');
        $lastMembership = DB::table('users')
            ->where('membership_id', 'like', $prefix . '%')
            ->max('membership_id');
        if ($lastMembership) {
            $lastSerial = (int) substr($lastMembership, -4);
        } else {
            $lastSerial = 0;
        }
        $techIds = DB::table('technologies')->pluck('id')->toArray();
        $occupationIds = DB::table('occupations')->pluck('id')->toArray();
        $membershipTypeIds = DB::table('membership_types')->pluck('id')->toArray();
        $relationshipIds = DB::table('relationships')->pluck('id')->toArray();

        $data = [];

        for ($i = 1; $i <= 100; $i++) {

            // ✅ Generate membership_id (unique + sequential)
            $serial = str_pad($lastSerial + $i, 4, '0', STR_PAD_LEFT);
            $membershipId = (int) ($prefix . $serial);

            // ❗ Optional limit protection
            if (($lastSerial + $i) > 9999) {
                throw new \Exception('Membership limit exceeded for this month');
            }

            $data[] = [
                'membership_id' => $membershipId,

                'user_name' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),

                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),

                'name' => $faker->name,
                'user_fathers_name' => $faker->name('male'),
                'user_mothers_name' => $faker->name('female'),

                'dob' => $faker->date('Y-m-d'),
                'id_type' => rand(1, 3),
                'national_identity_number' => $faker->numerify('##########'),

                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),

                'present_address_detail' => $faker->address,
                'permanent_address_details' => $faker->address,

                'contact_no' => $faker->phoneNumber,

                'tech_id' => !empty($techIds) ? $faker->randomElement($techIds) : null,
                'occupation_id' => !empty($occupationIds) ? $faker->randomElement($occupationIds) : null,

                'employer_name' => $faker->company,
                'designation' => $faker->jobTitle,
                'office_address_details' => $faker->address,

                'latest_degree_name' => $faker->randomElement(['BSc', 'MSc', 'Diploma', 'PhD']),
                'latest_institute_name' => $faker->company,

                'membership_type_id' => !empty($membershipTypeIds) ? $faker->randomElement($membershipTypeIds) : null,

                'ex_association' => rand(0, 1),
                'ex_association_details' => $faker->sentence,

                'emergency_contact_name' => $faker->name,
                'relationship_id' => !empty($relationshipIds) ? $faker->randomElement($relationshipIds) : null,
                'emergency_contact_no' => $faker->phoneNumber,

                'profile_image' => null,
                'signature' => null,

                // ✅ Your requirement
                'is_admin' => 0,
                'admin_role_id' => null,

                'data_status' => rand(0, 1),

                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($data);

    }
}
