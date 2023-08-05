<?php

declare(strict_types=1);

use Ramsey\Uuid\Uuid;

class UniqueId
{


    public function __invoke(bool $padding=true): array|bool|string|null
    {
        $uuid           = Uuid::uuid4();
        $processed_uuid = str_replace('-', '', $uuid->toString());
        $bin            = hex2bin($processed_uuid);
        $base32         = $this->toBase32($bin, $padding);
        $id             = str_replace('=', '', $base32);
        return mb_strtolower($id);

    }//end __invoke()


    private function toBase32(string $input, bool $padding): string
    {
        $map = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '=',
        ];

        if (empty($input)) {
            return '';
        }

        $input        = str_split($input);
        $binaryString = '';
        for ($i = 0; $i < count($input); $i++) {
            $binaryString .= str_pad(base_convert((string) ord($input[$i]), 10, 2), 8, '0', STR_PAD_LEFT);
        }

        $fiveBitBinaryArray = str_split($binaryString, 5);
        $base32             = '';
        $i                  = 0;
        while ($i < count($fiveBitBinaryArray)) {
            $base32 .= $map[base_convert(str_pad($fiveBitBinaryArray[$i], 5, '0'), 2, 10)];
            $i++;
        }

        if ($padding && ($x = strlen($binaryString) % 40) != 0) {
            if ($x == 8) {
                $base32 .= str_repeat($map[32], 6);
            } else if ($x == 16) {
                $base32 .= str_repeat($map[32], 4);
            } else if ($x == 24) {
                $base32 .= str_repeat($map[32], 3);
            } else if ($x == 32) {
                $base32 .= $map[32];
            }
        }

        return $base32;

    }//end toBase32()


}//end class
