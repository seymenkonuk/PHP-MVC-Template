<?php
// ============================================================================
// File:    validator.messages.en.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

const ERROR_MESSAGES = [
    // Integer errors
    'int_required' => "This field must be an integer!",

    // Double errors
    'double_required' => "This field must be a floating point number (double)!",

    // Boolean errors
    'bool_required' => "This field must be a boolean (true/false)!",

    // String errors
    'string_required' => "This field must be a string!",
    'string_length' => "This field must be {length} characters long!",
    'string_min_length' => "This field must be at least {length} characters long!",
    'string_max_length' => "This field must be at most {length} characters long!",

    // Mail errors
    'email_required' => "This field must be an email!",

    // DateTime errors
    'datetime_required' => "This field must be a datetime (Y-m-d H:i:s)!",
    'datetime_min' => "This field must be a date after {datetime}!",
    'datetime_max' => "This field must be a date before {datetime}!",

    // Array errors
    'array_required' => "This field must be an array!",
    'array_count' => "This list must contain {count} items!",
    'array_min_count' => "This list must contain at least {count} items!",
    'array_max_count' => "This list must contain at most {count} items!",
    'array_item_validation' => "Each item in this list must conform to the rule: {error_message}",

    // Value
    'constant_value' => "Value must be {number}!",
    'min_value' => "Value must be at least {number}!",
    'max_value' => "Value must be at most {number}!",

    // Enum
    'enum' => "This field can only accept one of the following values: {enum}.",

    // Required field error
    'required' => "This field cannot be empty!",

    // Generic error
    'invalid' => "This field is not allowed!",
];
