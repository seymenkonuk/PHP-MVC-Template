<?php
// ============================================================================
// File:    regex.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

// En Az 1 Büyük Harf (Türkçe Karakterler Dahil)
// En Az 1 Küçük Harf (Türkçe Karakterler Dahil)
// En Az 1 Özel Karakter (!@#$%^&*()_\-+=])
// En Az 8, En Fazla 20 Karakter
const PASSWORD_REGEX_ERROR = "Bu alan en az bir büyük harf, bir küçük harf, bir rakam ve bir özel karakter içermelidir!";
const PASSWORD_REGEX_RULE = "/^(?=.*[a-zçğıöşü])(?=.*[A-ZÇĞİÖŞÜ])(?=.*\d)(?=.*[!@#$%^&*()_\-+=])[A-Za-zçğıöşüÇĞİÖŞÜ\d!@#$%^&*()_\-+=]+$/u";
const PASSWORD_MIN_LEN =  8;
const PASSWORD_MAX_LEN = 20;

// A-Z, a-z, 0-9 ve Boşluk İçerebilir
// En Az 2, En Fazla 20 Karakter
const NAME_REGEX_ERROR = "Bu alan yalnızca Türkçe harfler ve boşluklar içerebilir!";
const NAME_REGEX_RULE = "/^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+$/u";
const NAME_MIN_LEN = 2;
const NAME_MAX_LEN = 20;

// A-Z, a-z, 0-9 İçerebilir
// En Az 5, En Fazla 20 Karakter
const CODE_REGEX_ERROR = "Bu alan yalnızca A-Z, a-z, 0-9 karakterlerini içerebilir!";
const CODE_REGEX_RULE = "/^[A-Za-z0-9]+$/u";
const CODE_MIN_LEN = 18;
const CODE_MAX_LEN = 18;

// A-Z, a-z, 0-9 ve Boşluk İçerebilir
// En Az 5, En Fazla 20 Karakter
const TITLE_REGEX_ERROR = "Bu alan yalnızca Türkçe harfler ve boşluklar içerebilir!";
const TITLE_REGEX_RULE = "/^[a-zA-ZçÇğĞıİöÖşŞüÜ\s]+$/u";
const TITLE_MIN_LEN = 5;
const TITLE_MAX_LEN = 20;

// Her Şeyi İçerebilir
// En Az 20, En Fazla 1000 Karakter
const DESC_REGEX_ERROR = "Bu alan beklenmedik karakterler içeriyor!";
const DESC_REGEX_RULE = "/^.*$/su";
const DESC_MIN_LEN = 20;
const DESC_MAX_LEN = 1000;
