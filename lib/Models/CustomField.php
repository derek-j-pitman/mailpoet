<?php
namespace MailPoet\Models;

if(!defined('ABSPATH')) exit;

class CustomField extends Model {
  public static $_table = MP_CUSTOM_FIELDS_TABLE;

  function __construct() {
    parent::__construct();
    $this->addValidations('name', array(
      'required' => __('You need to specify a name.')
    ));
    $this->addValidations('type', array(
      'required' => __('You need to specify a type.')
    ));
  }

  function asArray() {
    $model = parent::asArray();

    $model['params'] = (
      is_serialized($this->params)
      ? unserialize($this->params)
      : $this->params
    );

    return $model;
  }

  function save() {
    $this->set('params', (
      is_serialized($this->params)
      ? $this->params
      : serialize($this->params)
    ));
    return parent::save();
  }

  function subscribers() {
    return $this->has_many_through(
      __NAMESPACE__ . '\Subscriber',
      __NAMESPACE__ . '\SubscriberCustomField',
      'custom_field_id',
      'subscriber_id'
    )->select_expr(MP_SUBSCRIBER_CUSTOM_FIELD_TABLE.'.value');
  }
}