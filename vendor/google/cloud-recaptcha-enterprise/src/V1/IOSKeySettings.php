<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/cloud/recaptchaenterprise/v1/recaptchaenterprise.proto

namespace Google\Cloud\RecaptchaEnterprise\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Settings specific to keys that can be used by iOS apps.
 *
 * Generated from protobuf message <code>google.cloud.recaptchaenterprise.v1.IOSKeySettings</code>
 */
class IOSKeySettings extends \Google\Protobuf\Internal\Message
{
    /**
     * Optional. If set to true, allowed_bundle_ids are not enforced.
     *
     * Generated from protobuf field <code>bool allow_all_bundle_ids = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $allow_all_bundle_ids = false;
    /**
     * Optional. iOS bundle ids of apps allowed to use the key.
     * Example: 'com.companyname.productname.appname'
     *
     * Generated from protobuf field <code>repeated string allowed_bundle_ids = 1 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $allowed_bundle_ids;
    /**
     * Optional. Apple Developer account details for the app that is protected by
     * the reCAPTCHA Key. reCAPTCHA leverages platform-specific checks like Apple
     * App Attest and Apple DeviceCheck to protect your app from abuse. Providing
     * these fields allows reCAPTCHA to get a better assessment of the integrity
     * of your app.
     *
     * Generated from protobuf field <code>.google.cloud.recaptchaenterprise.v1.AppleDeveloperId apple_developer_id = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     */
    private $apple_developer_id = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $allow_all_bundle_ids
     *           Optional. If set to true, allowed_bundle_ids are not enforced.
     *     @type array<string>|\Google\Protobuf\Internal\RepeatedField $allowed_bundle_ids
     *           Optional. iOS bundle ids of apps allowed to use the key.
     *           Example: 'com.companyname.productname.appname'
     *     @type \Google\Cloud\RecaptchaEnterprise\V1\AppleDeveloperId $apple_developer_id
     *           Optional. Apple Developer account details for the app that is protected by
     *           the reCAPTCHA Key. reCAPTCHA leverages platform-specific checks like Apple
     *           App Attest and Apple DeviceCheck to protect your app from abuse. Providing
     *           these fields allows reCAPTCHA to get a better assessment of the integrity
     *           of your app.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Cloud\Recaptchaenterprise\V1\Recaptchaenterprise::initOnce();
        parent::__construct($data);
    }

    /**
     * Optional. If set to true, allowed_bundle_ids are not enforced.
     *
     * Generated from protobuf field <code>bool allow_all_bundle_ids = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return bool
     */
    public function getAllowAllBundleIds()
    {
        return $this->allow_all_bundle_ids;
    }

    /**
     * Optional. If set to true, allowed_bundle_ids are not enforced.
     *
     * Generated from protobuf field <code>bool allow_all_bundle_ids = 2 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param bool $var
     * @return $this
     */
    public function setAllowAllBundleIds($var)
    {
        GPBUtil::checkBool($var);
        $this->allow_all_bundle_ids = $var;

        return $this;
    }

    /**
     * Optional. iOS bundle ids of apps allowed to use the key.
     * Example: 'com.companyname.productname.appname'
     *
     * Generated from protobuf field <code>repeated string allowed_bundle_ids = 1 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAllowedBundleIds()
    {
        return $this->allowed_bundle_ids;
    }

    /**
     * Optional. iOS bundle ids of apps allowed to use the key.
     * Example: 'com.companyname.productname.appname'
     *
     * Generated from protobuf field <code>repeated string allowed_bundle_ids = 1 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param array<string>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAllowedBundleIds($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->allowed_bundle_ids = $arr;

        return $this;
    }

    /**
     * Optional. Apple Developer account details for the app that is protected by
     * the reCAPTCHA Key. reCAPTCHA leverages platform-specific checks like Apple
     * App Attest and Apple DeviceCheck to protect your app from abuse. Providing
     * these fields allows reCAPTCHA to get a better assessment of the integrity
     * of your app.
     *
     * Generated from protobuf field <code>.google.cloud.recaptchaenterprise.v1.AppleDeveloperId apple_developer_id = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @return \Google\Cloud\RecaptchaEnterprise\V1\AppleDeveloperId|null
     */
    public function getAppleDeveloperId()
    {
        return $this->apple_developer_id;
    }

    public function hasAppleDeveloperId()
    {
        return isset($this->apple_developer_id);
    }

    public function clearAppleDeveloperId()
    {
        unset($this->apple_developer_id);
    }

    /**
     * Optional. Apple Developer account details for the app that is protected by
     * the reCAPTCHA Key. reCAPTCHA leverages platform-specific checks like Apple
     * App Attest and Apple DeviceCheck to protect your app from abuse. Providing
     * these fields allows reCAPTCHA to get a better assessment of the integrity
     * of your app.
     *
     * Generated from protobuf field <code>.google.cloud.recaptchaenterprise.v1.AppleDeveloperId apple_developer_id = 3 [(.google.api.field_behavior) = OPTIONAL];</code>
     * @param \Google\Cloud\RecaptchaEnterprise\V1\AppleDeveloperId $var
     * @return $this
     */
    public function setAppleDeveloperId($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\RecaptchaEnterprise\V1\AppleDeveloperId::class);
        $this->apple_developer_id = $var;

        return $this;
    }

}

