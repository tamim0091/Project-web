<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/recaptchaenterprise/v1/recaptchaenterprise.proto

namespace Google\Cloud\RecaptchaEnterprise\V1\SmsTollFraudVerdict;

use UnexpectedValueException;

/**
 * Reasons contributing to the SMS toll fraud verdict.
 *
 * Protobuf type <code>google.cloud.recaptchaenterprise.v1.SmsTollFraudVerdict.SmsTollFraudReason</code>
 */
class SmsTollFraudReason
{
    /**
     * Default unspecified reason
     *
     * Generated from protobuf enum <code>SMS_TOLL_FRAUD_REASON_UNSPECIFIED = 0;</code>
     */
    const SMS_TOLL_FRAUD_REASON_UNSPECIFIED = 0;
    /**
     * The provided phone number was invalid
     *
     * Generated from protobuf enum <code>INVALID_PHONE_NUMBER = 1;</code>
     */
    const INVALID_PHONE_NUMBER = 1;

    private static $valueToName = [
        self::SMS_TOLL_FRAUD_REASON_UNSPECIFIED => 'SMS_TOLL_FRAUD_REASON_UNSPECIFIED',
        self::INVALID_PHONE_NUMBER => 'INVALID_PHONE_NUMBER',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SmsTollFraudReason::class, \Google\Cloud\RecaptchaEnterprise\V1\SmsTollFraudVerdict_SmsTollFraudReason::class);

