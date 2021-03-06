<?php

namespace MailPoet\Newsletter\Shortcodes\Categories;

use MailPoet\Entities\NewsletterEntity;
use MailPoet\Entities\SendingQueueEntity;
use MailPoet\Entities\SubscriberCustomFieldEntity;
use MailPoet\Entities\SubscriberEntity;
use MailPoet\Subscribers\SubscriberCustomFieldRepository;
use MailPoet\Subscribers\SubscribersRepository;
use MailPoet\WP\Functions as WPFunctions;

class Subscriber implements CategoryInterface {

  /** @var SubscribersRepository */
  private $subscribersRepository;

  /** @var SubscriberCustomFieldRepository */
  private $subscriberCustomFieldRepository;

  public function __construct(
    SubscribersRepository $subscribersRepository,
    SubscriberCustomFieldRepository $subscriberCustomFieldRepository
  ) {
    $this->subscribersRepository = $subscribersRepository;
    $this->subscriberCustomFieldRepository = $subscriberCustomFieldRepository;
  }

  public function process(
    array $shortcodeDetails,
    NewsletterEntity $newsletter = null,
    SubscriberEntity $subscriber = null,
    SendingQueueEntity $queue = null,
    string $content = '',
    bool $wpUserPreview = false
  ): ?string {
    $defaultValue = ($shortcodeDetails['action_argument'] === 'default') ?
      $shortcodeDetails['action_argument_value'] :
      '';
    switch ($shortcodeDetails['action']) {
      case 'firstname':
        return (($subscriber instanceof SubscriberEntity) && !empty($subscriber->getFirstName())) ? $subscriber->getFirstName() : $defaultValue;
      case 'lastname':
        return (($subscriber instanceof SubscriberEntity) && !empty($subscriber->getLastName())) ? $subscriber->getLastName() : $defaultValue;
      case 'email':
        return ($subscriber instanceof SubscriberEntity) ? $subscriber->getEmail() : $defaultValue;
      case 'displayname':
        if (($subscriber instanceof SubscriberEntity) && $subscriber->getWpUserId()) {
          $wpUser = WPFunctions::get()->getUserdata($subscriber->getWpUserId());
          return $wpUser->user_login; // phpcs:ignore Squiz.NamingConventions.ValidVariableName.NotCamelCaps
        }
        return $defaultValue;
      case 'count':
        return (string)$this->subscribersRepository->getTotalSubscribers();
      default:
        if (preg_match('/cf_(\d+)/', $shortcodeDetails['action'], $customField) &&
          !empty($subscriber->getId())
        ) {
          $customField = $this->subscriberCustomFieldRepository->findOneBy([
            'subscriber' => $subscriber,
            'customField' => $customField[1],
          ]);
          return ($customField instanceof SubscriberCustomFieldEntity) ? $customField->getValue() : null;
        }
        return null;
    }
  }
}
