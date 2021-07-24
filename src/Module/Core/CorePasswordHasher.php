<?php

namespace App\Module\Core;


abstract class CorePasswordHasher
{
    public static function hashPassword(String $Password): String
    {


        $SaltIndex = 0;
        $LengthSequance = [95, 251, 210, 170, 252, 76, 59, 188, 87, 50, 132, 145, 106, 39, 140, 175, 196, 136, 207, 91, 237, 71, 62, 164, 243, 163, 40, 176, 8, 14, 101, 221, 15, 9, 122, 160, 97, 171, 224, 29, 205, 212, 94, 155, 222, 137, 51, 27, 110, 58, 107, 231, 240, 78, 90, 100, 217, 126, 34, 109, 20, 4, 38, 186, 172, 42, 159, 46, 202, 158, 11, 79, 228, 198, 214, 33, 134, 253, 204, 35, 174, 157, 41, 69, 60, 226, 73, 219, 118, 21, 244, 139, 64, 249, 112, 213, 182, 85, 84, 72, 245, 129, 47, 144, 54, 102, 178, 89, 183, 153, 123, 2, 65, 93, 227, 43, 184, 104, 13, 103, 194, 138, 6, 185, 92, 45, 250, 180, 66, 52, 154, 125, 10, 83, 32, 82, 147, 56, 99, 239, 17, 67, 74, 5, 115, 130, 16, 133, 88, 177, 61, 114, 208, 111, 108, 7, 121, 216, 150, 225, 142, 146, 49, 116, 131, 230, 30, 55, 19, 24, 23, 31, 197, 81, 18, 57, 120, 211, 187, 117, 151, 193, 179, 190, 189, 238, 77, 119, 167, 162, 143, 209, 25, 248, 1, 12, 254, 127, 156, 48, 161, 141, 192, 68, 105, 199, 149, 195, 234, 242, 169, 255, 44, 152, 98, 220, 28, 37, 96, 113, 246, 86, 233, 200, 247, 218, 135, 215, 236, 26, 165, 181, 70, 22, 148, 168, 241, 206, 235, 53, 223, 173, 124, 166, 3, 63, 229, 203, 36, 232, 75, 128, 191, 201, 80];

        for ($i = 0; $i < strlen($Password); $i++)
            $SaltIndex = $SaltIndex ^ ord($Password[$i]);

        $Result = array_fill(0, strlen($Password) + $LengthSequance[$SaltIndex], 0);

        for ($i = 0; $i < strlen($Password); $i++) {
            $ExtendIndex = $i & ~255;
            $Part = $i >= (count($Result) & ~255) ? (count($Result) & 255) : 255;

            $Result[(($LengthSequance[$i % count($LengthSequance)] + ord($Password[$i])) % $Part) + $ExtendIndex] = $LengthSequance[$i % count($LengthSequance)] ^ ord($Password[$i]);
        }

        for ($i = 0; $i < count($Result); $i++) {
            if (!$Result[$i])
                $Result[$i] = $LengthSequance[$i % count($LengthSequance)];
        }

        $String = "";
        for ($i = 0; $i < count($Result); $i++)
            $String[$i] = chr($Result[$i]);

        return hash("sha256", $String);

    }
}