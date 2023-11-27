<?php

namespace Database\Seeders;

use App\Models\image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Icons = [
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098364/pexels-pixabay-261102_sw2quz.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098363/photo-1625244724120-1fd1d34d00f6_jepwyi.avif",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098363/photo-1625244724120-1fd1d34d00f6_jepwyi.avif",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098363/photo-1630660664869-c9d3cc676880_ofib5a.avif",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098362/pexels-asman-chema-594077_h5u4xu.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098360/modern-orange-hotel-building-blue-sky-background-3d-rendering_476612-19049_dntnol.avif",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098360/modern-orange-hotel-building-blue-sky-background-3d-rendering_476612-19049_dntnol.avif",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098360/hotel-room-beautiful-orange-sofa-included-43642330_ehoreh.webp",
            ],
            [
                "image" => "hhttps://res.cloudinary.com/dzqywzres/image/upload/v1701098358/hh-restaurants-brumus-1_cwpojn.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/56009974_a7f12f.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/274803_Dobbelt-vaerelse-hotel-oasia-aarhus_iistvj.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098358/Hotel-La-Reserve-Saint-Jean-de-Luz-_Alexandre-Chapelier-Chambre-Privile%CC%80ge-2-1280x850_mflolw.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098356/360_F_29133877_bfA2n7cWV53fto2BomyZ6pyRujJTBwjd_dy9oxw.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098357/56009974_a7f12f.jpg",
            ],
            [
                "image" => "https://res.cloudinary.com/dzqywzres/image/upload/v1701098356/360_F_65821315_WGpXLhFtlEHfGQ8sqJ5RUNFNmnYDGgOd_l17pcj.jpg",
            ],

        ];
        image::query()->insert($Icons);
    }
}
