<?php
// ============================================================================
// File:    validator.messages.tr.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

const ERROR_MESSAGES = [
    // Integer errors
    'int_required' => "Bu alan bir tamsayı (int) olmalı!",

    // Double errors
    'double_required' => "Bu alan bir ondalıklı sayı (double) olmalı!",

    // Boolean errors
    'bool_required' => "Bu alan bir boolean (true/false) olmalı!",

    // String errors
    'string_required' => "Bu alan bir metin (string) olmalı!",
    'string_length' => "Bu alan {length} karakter olmalı!",
    'string_min_length' => "Bu alan en az {length} karakter olmalı!",
    'string_max_length' => "Bu alan en fazla {length} karakter olmalı!",

    // Mail errors
    'email_required' => "Bu alan bir email olmalı!",

    // DateTime errors
    'datetime_required' => "Bu alan bir datetime (Y-m-d H:i:s) olmalı!",
    'datetime_min' => "Bu alan {datetime} tarihinden sonraki bir tarih olmalı!",
    'datetime_max' => "Bu alan {datetime} tarihinden önceki bir tarih olmalı!",

    // Array errors
    'array_required' => "Bu alan bir liste (array) olmalı!",
    'array_count' => "Bu liste {count} eleman içermelidir!",
    'array_min_count' => "Bu liste en az {count} eleman içermelidir!",
    'array_max_count' => "Bu liste en fazla {count} eleman içermelidir!",
    'array_item_validation' => "Bu listedeki her alan belirtilen kurala uymalı: {error_message}",

    // Value
    'constant_value' => "Değer {number} olmalı!",
    'min_value' => "Değer en az {number} olmalı!",
    'max_value' => "Değer en fazla {number} olmalı!",

    // Enum
    'enum' => "Bu alan sadece {enum} değerlerinden birini alabilir.",

    // Required field error
    'required' => "Bu alan boş bırakılamaz!",

    // Generic error
    'invalid' => "Bu alan kabul edilmiyor!",
];
