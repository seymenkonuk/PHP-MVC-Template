<?php
// ============================================================================
// File:    Validator.php
// Author:  Recep Seymen Konuk <konukrecepseymen@gmail.com>
//
// Licensed under the terms of the LICENSE file in the project root directory.
// ============================================================================

require_once(__DIR__ . DIRECTORY_SEPARATOR . "validator.messages.tr.php");

class Validator
{
    public const NEGATIVE_INFINITY = -1;
    public const POSITIVE_INFINITY = -1;

    private bool $is_require_field;    // require field, optional field

    private string $value_type;        // int, double, str, bool, email, datetime, special
    private float|int|DateTime $value_min;
    private float|int|DateTime $value_max;
    private array $value_allowed;
    private array $value_regex_rules;

    private bool $is_array;            // array<$value_type>
    private int $array_min_length;
    private int $array_max_length;

    private Closure $special_checker;  // ($value) => { return "error_message"; }

    // Static Factory method
    public static function create()
    {
        return new self();
    }

    // Constructor
    private function __construct()
    {
        $this->is_require_field = false;

        $this->value_type = "str";
        $this->value_min = 0;
        $this->value_max = 255;
        $this->value_allowed = [];
        $this->value_regex_rules = [];

        $this->is_array = false;
        $this->array_min_length = Validator::NEGATIVE_INFINITY;
        $this->array_max_length = Validator::POSITIVE_INFINITY;

        $this->special_checker = function ($value) {
            return "";
        };
    }

    // Value Type
    public function int()
    {
        $this->value_type = "int";
        $this->value_min = PHP_INT_MIN;
        $this->value_max = PHP_INT_MAX;
        return $this;
    }
    public function double()
    {
        $this->value_type = "double";
        $this->value_min = PHP_FLOAT_MIN;
        $this->value_max = PHP_FLOAT_MAX;
        return $this;
    }
    public function str()
    {
        $this->value_type = "str";
        $this->value_min = 0;
        $this->value_max = 255;
        return $this;
    }
    public function bool()
    {
        $this->value_type = "bool";
        $this->value_min = 0;
        $this->value_max = 0;
        return $this;
    }
    public function email()
    {
        $this->value_type = "email";
        $this->value_min = 0;
        $this->value_max = 0;
        return $this;
    }
    public function datetime()
    {
        $this->value_type = "datetime";
        $this->value_min = new DateTime('@0');
        $this->value_max = new DateTime('9999-12-31 23:59:59');
        return $this;
    }
    public function special()
    {
        $this->value_type = "special";
        $this->value_min = 0;
        $this->value_max = 0;
        return $this;
    }

    // Min / Max Value
    public function min(float|int|DateTime $min_value)
    {
        $this->value_min = $min_value;
        return $this;
    }
    public function max(float|int|DateTime $max_value)
    {
        $this->value_max = $max_value;
        return $this;
    }

    // Insert Allow Value
    // if array is empty then can be anything
    public function insertAllowValue($value)
    {
        $this->value_allowed[] = $value;
        return $this;
    }

    // Insert Regex Rule
    // if array is empty then no rule
    public function insertRegexRule(string $rule_error, string $regex)
    {
        $this->value_regex_rules[] = [
            "error" => $rule_error,
            "regex" => $regex
        ];
        return $this;
    }

    // Array
    public function array(int $min = Validator::NEGATIVE_INFINITY, int $max = Validator::POSITIVE_INFINITY)
    {
        $this->is_array = true;
        if ($min >= -1 && ($max == -1 || $min <= $max)) {
            $this->array_min_length = $min;
            $this->array_max_length = $max;
        }
        return $this;
    }

    // Require / Optional
    public function require()
    {
        $this->is_require_field = true;
        return $this;
    }
    public function optional()
    {
        $this->is_require_field = false;
        return $this;
    }

    // Special Checker
    public function editSpecialChecker(Closure $special_checker)
    {
        $this->special_checker = $special_checker;
        return $this;
    }

    // Check Value
    private function checkInt($value)
    {
        // Check Type
        if (!is_int($value)) {
            return Validator::formatText(ERROR_MESSAGES["int_required"], [], []);
        }
        // Check Allow Values
        if (!empty($this->value_allowed) && !in_array($value, $this->value_allowed, true)) {
            return Validator::formatText(
                ERROR_MESSAGES["enum"],
                ["{enum}"],
                [json_encode($this->value_allowed, JSON_UNESCAPED_UNICODE)]
            );
        }
        // Check Regex Rule
        foreach ($this->value_regex_rules as $regex_rule) {
            if (preg_match($regex_rule["regex"], strval($value)) !== 1) {
                return $regex_rule["error"];
            }
        }
        // Check Min / Max Value
        if ($this->value_min == $this->value_max && $value != $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["constant_value"], ["{number}"], [$this->value_min]);
        }
        if ($value < $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["value_min"], ["{number}"], [$this->value_min]);
        }
        if ($value > $this->value_max) {
            return Validator::formatText(ERROR_MESSAGES["value_max"], ["{number}"], [$this->value_max]);
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function checkDouble($value)
    {
        // Check Type
        if (!is_double($value)) {
            return Validator::formatText(ERROR_MESSAGES["double_required"], [], []);
        }
        // Check Allow Values
        if (!empty($this->value_allowed) && !in_array($value, $this->value_allowed, true)) {
            return Validator::formatText(
                ERROR_MESSAGES["enum"],
                ["{enum}"],
                [json_encode($this->value_allowed, JSON_UNESCAPED_UNICODE)]
            );
        }
        // Check Regex Rule
        foreach ($this->value_regex_rules as $regex_rule) {
            if (preg_match($regex_rule["regex"], strval($value)) !== 1) {
                return $regex_rule["error"];
            }
        }
        // Check Min / Max Value
        if ($this->value_min == $this->value_max && $value != $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["constant_value"], ["{number}"], [$this->value_min]);
        }
        if ($value < $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["value_min"], ["{number}"], [$this->value_min]);
        }
        if ($value > $this->value_max) {
            return Validator::formatText(ERROR_MESSAGES["value_max"], ["{number}"], [$this->value_max]);
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function checkStr($value)
    {
        // Check Type
        if (!is_string($value)) {
            return Validator::formatText(ERROR_MESSAGES["string_required"], [], []);
        }
        // Check Allow Values
        if (!empty($this->value_allowed) && !in_array($value, $this->value_allowed, true)) {
            return Validator::formatText(
                ERROR_MESSAGES["enum"],
                ["{enum}"],
                [json_encode($this->value_allowed, JSON_UNESCAPED_UNICODE)]
            );
        }
        // Check Regex Rule
        foreach ($this->value_regex_rules as $regex_rule) {
            if (preg_match($regex_rule["regex"], strval($value)) !== 1) {
                return $regex_rule["error"];
            }
        }
        // Check Min / Max Value
        if ($this->value_min == $this->value_max && mb_strlen($value) != $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["string_length"], ["{length}"], [$this->value_min]);
        }
        if (mb_strlen($value) < $this->value_min) {
            return Validator::formatText(ERROR_MESSAGES["string_min_length"], ["{length}"], [$this->value_min]);
        }
        if (mb_strlen($value) > $this->value_max) {
            return Validator::formatText(ERROR_MESSAGES["string_max_length"], ["{length}"], [$this->value_max]);
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function checkBool($value)
    {
        // Check Type
        if (!is_bool($value)) {
            return Validator::formatText(ERROR_MESSAGES["bool_required"], [], []);
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function checkEmail($value)
    {
        // Check Type
        if (!is_string($value)) {
            return Validator::formatText(ERROR_MESSAGES["email_required"], [], []);
        }
        // Check Email Format
        if ($value != filter_var($value, FILTER_SANITIZE_EMAIL)) {
            return Validator::formatText(ERROR_MESSAGES["email_required"], [], []);
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return Validator::formatText(ERROR_MESSAGES["email_required"], [], []);
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function checkDatetime($value)
    {
        // Check Type
        if (!Validator::isValidDateTime($value)) {
            return Validator::formatText(ERROR_MESSAGES["datetime_required"], [], []);
        }
        // Check Min / Max Value
        if (!Validator::isAfterDate($value, $this->value_min)) {
            return Validator::formatText(
                ERROR_MESSAGES["datetime_min"],
                ["{datetime}"],
                [$this->value_min->format('Y-m-d H:i:s')]
            );
        }
        if (!Validator::isBeforeDate($value, $this->value_max)) {
            return Validator::formatText(
                ERROR_MESSAGES["datetime_max"],
                ["{datetime}"],
                [$this->value_min->format('Y-m-d H:i:s')]
            );
        }
        // Check Special Rule
        return ($this->special_checker)($value);
    }

    private function check_list($value)
    {
        // Check Type
        if (!is_array($value) || Validator::isAssociativeArray($value)) {
            return Validator::formatText(ERROR_MESSAGES["array_required"], [], []);
        }
        // Check Min / Max
        if ($this->array_min_length != -1 && $this->array_min_length == $this->array_max_length && count($value) != $this->array_min_length) {
            return Validator::formatText(
                ERROR_MESSAGES["array_count"],
                ["{count}"],
                [$this->array_min_length]
            );
        }
        if ($this->array_min_length != -1 && count($value) < $this->array_min_length) {
            return Validator::formatText(
                ERROR_MESSAGES["array_min_count"],
                ["{count}"],
                [$this->array_min_length]
            );
        }
        if ($this->array_max_length != -1 && count($value) > $this->array_max_length) {
            return Validator::formatText(
                ERROR_MESSAGES["array_max_count"],
                ["{count}"],
                [$this->array_max_length]
            );
        }
        // Check Item Validation
        foreach ($value as $val) {
            $errorMessage = $this->checkValue($val);
            if ($errorMessage != "") {
                return Validator::formatText(
                    ERROR_MESSAGES["array_item_validation"],
                    ["{error_message}"],
                    [$errorMessage]
                );
            }
        }
    }

    private function checkValue($value)
    {
        if ($this->value_type === "int") {
            return $this->checkInt($value);
        }
        if ($this->value_type === "double") {
            return $this->checkDouble($value);
        }
        if ($this->value_type === "str") {
            return $this->checkStr($value);
        }
        if ($this->value_type === "bool") {
            return $this->checkBool($value);
        }
        if ($this->value_type === "email") {
            return $this->checkEmail($value);
        }
        if ($this->value_type === "datetime") {
            return $this->checkDatetime($value);
        }
        return ($this->special_checker)($value);
    }

    private function check($value)
    {
        if ($value === NULL) {
            if ($this->is_require_field) {
                return Validator::formatText(ERROR_MESSAGES["required"], [], []);
            }
            return "";
        }
        if ($this->is_array) {
            return $this->check_list($value);
        }
        return $this->checkValue($value);
    }

    private static function setErrorMessage(&$errorArray, $errorKeys, $errorMessage)
    {
        $tempArray = &$errorArray;
        foreach ($errorKeys as $value) {
            if (!isset($tempArray[$value])) {
                $tempArray[$value] = [];
            }
            $tempArray = &$tempArray[$value];
        }
        $tempArray = $errorMessage;
    }

    private static function validateDataWithError($tempData, $data, &$errorArray, $errorKeys)
    {
        if ($tempData instanceof Validator) {
            $errorMessage = $tempData->check($data);
            if ($errorMessage != "") {
                self::setErrorMessage($errorArray, $errorKeys, $errorMessage);
            }
            return;
        }
        // tempData geçerli formatta değil!
        if (!is_array($tempData)) {
            http_response_code(500);
            exit;
        }
        // Datada Olup, Şablonda Olmayan Varsa Hatalıdır!
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (!isset($tempData[$key])) {
                    $errorKeys[] = $key;
                    self::setErrorMessage($errorArray, $errorKeys, ERROR_MESSAGES["invalid"]);
                    array_pop($errorKeys);
                }
            }
        }
        // Şablonda Olup, Datada Olmayan Varsa Hatalıdır
        foreach ($tempData as $key => $value) {
            $errorKeys[] = $key;
            self::validateDataWithError($value, $data[$key] ?? NULL, $errorArray, $errorKeys);
            array_pop($errorKeys);
        }
    }

    public static function validateData($tempData, $data)
    {
        $errorArray = [];
        $tempData = $tempData ?? [];
        $data = $data ?? [];
        self::validateDataWithError($tempData, $data, $errorArray, []);
        return $errorArray;
    }

    private static function isAssociativeArray($array)
    {
        if (empty($array)) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private static function isValidDateTime(string $dateTimeString): bool
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
        $errors = DateTime::getLastErrors();
        return $date && (!$errors || ($errors['warning_count'] === 0 && $errors['error_count'] === 0));
    }

    private static function isAfterDate(string $dateTimeString, DateTime $referenceDate): bool
    {
        $dateToCheck = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
        return $dateToCheck >= $referenceDate;
    }

    private static function isBeforeDate(string $dateTimeString, DateTime $referenceDate): bool
    {
        $dateToCheck = DateTime::createFromFormat('Y-m-d H:i:s', $dateTimeString);
        return $dateToCheck <= $referenceDate;
    }

    private static function formatText(string $text, array $keys, array $values): string
    {
        return str_replace($keys, $values, $text);
    }
}
