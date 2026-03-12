<?php

/**
 * Input Validator - Reusable validation helper class
 * Provides common validation methods for API endpoints
 */
class Validator
{
    /**
     * Validate integer within range
     * @param mixed $value The value to validate
     * @param int $min Minimum allowed value
     * @param int $max Maximum allowed value
     * @return array ['valid' => bool, 'value' => int|null, 'error' => string|null]
     */
    public static function validateInt($value, $min = null, $max = null)
    {
        $int = filter_var($value, FILTER_VALIDATE_INT);
        
        if ($int === false) {
            return ['valid' => false, 'value' => null, 'error' => 'Invalid integer value'];
        }
        
        if ($min !== null && $int < $min) {
            return ['valid' => false, 'value' => null, 'error' => "Value must be at least $min"];
        }
        
        if ($max !== null && $int > $max) {
            return ['valid' => false, 'value' => null, 'error' => "Value must not exceed $max"];
        }
        
        return ['valid' => true, 'value' => $int, 'error' => null];
    }

    /**
     * Validate string length
     * @param string $value The value to validate
     * @param int $minLength Minimum length
     * @param int $maxLength Maximum length
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    public static function validateString($value, $minLength = 0, $maxLength = 255)
    {
        if (!is_string($value)) {
            return ['valid' => false, 'value' => null, 'error' => 'Must be a string'];
        }
        
        $trimmed = trim($value);
        $length = strlen($trimmed);
        
        if ($length < $minLength) {
            return ['valid' => false, 'value' => null, 'error' => "Must be at least $minLength characters"];
        }
        
        if ($length > $maxLength) {
            return ['valid' => false, 'value' => null, 'error' => "Must not exceed $maxLength characters"];
        }
        
        return ['valid' => true, 'value' => $trimmed, 'error' => null];
    }

    /**
     * Validate email address
     * @param string $email The email to validate
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    public static function validateEmail($email)
    {
        $email = trim($email);
        
        if (empty($email)) {
            return ['valid' => false, 'value' => null, 'error' => 'Email is required'];
        }
        
        $validated = filter_var($email, FILTER_VALIDATE_EMAIL);
        
        if ($validated === false) {
            return ['valid' => false, 'value' => null, 'error' => 'Invalid email format'];
        }
        
        if (strlen($email) > 100) {
            return ['valid' => false, 'value' => null, 'error' => 'Email too long'];
        }
        
        return ['valid' => true, 'value' => $validated, 'error' => null];
    }

    /**
     * Validate value is in allowed list
     * @param mixed $value The value to validate
     * @param array $allowedValues Array of allowed values
     * @param bool $strict Use strict comparison
     * @return array ['valid' => bool, 'value' => mixed|null, 'error' => string|null]
     */
    public static function validateInList($value, array $allowedValues, $strict = true)
    {
        if (in_array($value, $allowedValues, $strict)) {
            return ['valid' => true, 'value' => $value, 'error' => null];
        }
        
        return ['valid' => false, 'value' => null, 'error' => 'Invalid value. Allowed: ' . implode(', ', $allowedValues)];
    }

    /**
     * Validate mobile number (Sri Lankan format)
     * @param string $mobile The mobile number to validate
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    public static function validateMobile($mobile)
    {
        $mobile = trim($mobile);
        
        // Remove spaces, dashes, and plus signs
        $mobile = preg_replace('/[\s\-\+]/', '', $mobile);
        
        // Check if it's numeric
        if (!ctype_digit($mobile)) {
            return ['valid' => false, 'value' => null, 'error' => 'Mobile number must contain only digits'];
        }
        
        // Sri Lankan mobile: 10 digits starting with 07
        if (strlen($mobile) === 10 && substr($mobile, 0, 2) === '07') {
            return ['valid' => true, 'value' => $mobile, 'error' => null];
        }
        
        // International format: 94xxxxxxxxx (12 digits)
        if (strlen($mobile) === 11 && substr($mobile, 0, 2) === '94') {
            return ['valid' => true, 'value' => $mobile, 'error' => null];
        }
        
        return ['valid' => false, 'value' => null, 'error' => 'Invalid mobile number format. Use 07XXXXXXXX or 94XXXXXXXXX'];
    }

    /**
     * Validate required field
     * @param mixed $value The value to validate
     * @param string $fieldName Name of the field for error message
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function required($value, $fieldName = 'Field')
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            return ['valid' => false, 'error' => "$fieldName is required"];
        }
        
        return ['valid' => true, 'error' => null];
    }

    /**
     * Sanitize HTML to prevent XSS
     * @param string $html The HTML string to sanitize
     * @return string Sanitized string
     */
    public static function sanitizeHtml($html)
    {
        return htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate password strength
     * @param string $password The password to validate
     * @param int $minLength Minimum length
     * @return array ['valid' => bool, 'error' => string|null]
     */
    public static function validatePassword($password, $minLength = 8)
    {
        if (strlen($password) < $minLength) {
            return ['valid' => false, 'error' => "Password must be at least $minLength characters"];
        }
        
        // Check for at least one letter and one number
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return ['valid' => false, 'error' => 'Password must contain both letters and numbers'];
        }
        
        return ['valid' => true, 'error' => null];
    }

    /**
     * Validate positive decimal/float (for prices, etc.)
     * @param mixed $value The value to validate
     * @param float $min Minimum value
     * @param float $max Maximum value
     * @return array ['valid' => bool, 'value' => float|null, 'error' => string|null]
     */
    public static function validateDecimal($value, $min = 0, $max = null)
    {
        $decimal = filter_var($value, FILTER_VALIDATE_FLOAT);
        
        if ($decimal === false) {
            return ['valid' => false, 'value' => null, 'error' => 'Invalid decimal value'];
        }
        
        if ($decimal < $min) {
            return ['valid' => false, 'value' => null, 'error' => "Value must be at least $min"];
        }
        
        if ($max !== null && $decimal > $max) {
            return ['valid' => false, 'value' => null, 'error' => "Value must not exceed $max"];
        }
        
        return ['valid' => true, 'value' => $decimal, 'error' => null];
    }

    /**
     * Validate date format
     * @param string $date The date string to validate
     * @param string $format Expected format (default: Y-m-d)
     * @return array ['valid' => bool, 'value' => string|null, 'error' => string|null]
     */
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        
        if ($d && $d->format($format) === $date) {
            return ['valid' => true, 'value' => $date, 'error' => null];
        }
        
        return ['valid' => false, 'value' => null, 'error' => "Invalid date format. Expected: $format"];
    }

    /**
     * Batch validate multiple fields
     * @param array $rules Array of validation rules
     * @return array ['valid' => bool, 'errors' => array]
     * 
     * Example:
     * $rules = [
     *     'email' => ['value' => $_POST['email'], 'type' => 'email'],
     *     'age' => ['value' => $_POST['age'], 'type' => 'int', 'min' => 18, 'max' => 100]
     * ];
     */
    public static function validateBatch(array $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $rule['value'] ?? null;
            $type = $rule['type'] ?? 'string';
            
            // Check if required
            if (isset($rule['required']) && $rule['required']) {
                $required = self::required($value, $field);
                if (!$required['valid']) {
                    $errors[$field] = $required['error'];
                    continue;
                }
            }
            
            // Skip validation if empty and not required
            if (empty($value) && (!isset($rule['required']) || !$rule['required'])) {
                continue;
            }
            
            // Validate based on type
            switch ($type) {
                case 'int':
                case 'integer':
                    $result = self::validateInt($value, $rule['min'] ?? null, $rule['max'] ?? null);
                    break;
                case 'string':
                    $result = self::validateString($value, $rule['minLength'] ?? 0, $rule['maxLength'] ?? 255);
                    break;
                case 'email':
                    $result = self::validateEmail($value);
                    break;
                case 'mobile':
                    $result = self::validateMobile($value);
                    break;
                case 'password':
                    $result = self::validatePassword($value, $rule['minLength'] ?? 8);
                    break;
                case 'decimal':
                case 'float':
                    $result = self::validateDecimal($value, $rule['min'] ?? 0, $rule['max'] ?? null);
                    break;
                case 'inList':
                    $result = self::validateInList($value, $rule['allowedValues'] ?? [], $rule['strict'] ?? true);
                    break;
                case 'date':
                    $result = self::validateDate($value, $rule['format'] ?? 'Y-m-d');
                    break;
                default:
                    $result = ['valid' => true, 'error' => null];
            }
            
            if (!$result['valid']) {
                $errors[$field] = $result['error'];
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
}
