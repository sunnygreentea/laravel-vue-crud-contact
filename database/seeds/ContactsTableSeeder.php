<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Contact;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for($i = 1; $i <= 50; $i++) {
        	$first_name = $faker->firstName;
        	$last_name = $faker->lastName;
        	$email = $first_name.'.'.$last_name.'@gmail.com';
        	$phone = $faker->phoneNumber;
            Contact::create([
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'email'				=> $email,
                'phone'				=> $phone,
            ]);
        }
    }
}
