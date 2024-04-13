<?php

namespace Database\Factories;

use App\Models\Vote;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Torann\GeoIP\Facades\GeoIP;
use function Laravel\Prompts\error;

/**
 * @extends Factory<Vote>
 */
class VoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {
        $defaultCity = 'London';
        $defaultCountry = 'UK';
        $reserved = [
            [0, 255],
            [10, 10],
            [100, 100],
            [127, 127],
            [169, 169],
            [172, 172],
            [192, 192],
            [198, 198],
            [203, 203],
            [224, 239],
            [240, 255]
        ];

        do {
            $firstOctet = mt_rand(1, 254);
        } while (in_array([$firstOctet, $firstOctet], $reserved));

        $ip = $firstOctet . '.' .
            mt_rand(0, 255) . '.' .
            mt_rand(0, 255) . '.' .
            mt_rand(1, 254);

        try {
            $location = geoip()->getLocation($ip);
        } catch (Exception $e) {
            error('Issues working with geoip: ' . $e->getMessage());
        }

        return [
            'option_id' => $this->faker->numberBetween(1, 4),
            'created_at' => now(),
            'updated_at' => now(),
            'ip_address' => $ip,
            'location' => !empty($location) ?
                ($location->city . ', ' . $location->country) :
                ($defaultCity . ', ' . $defaultCountry),
        ];
    }
}
