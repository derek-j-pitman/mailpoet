<?php

namespace MailPoet\Entities;

use MailPoet\Doctrine\EntityTraits\AutoincrementedIdTrait;
use MailPoet\Doctrine\EntityTraits\CreatedAtTrait;
use MailPoet\Doctrine\EntityTraits\UpdatedAtTrait;
use MailPoetVendor\Doctrine\ORM\Mapping as ORM;
use MailPoetVendor\Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="newsletter_templates")
 */
class NewsletterTemplateEntity {
  use AutoincrementedIdTrait;
  use CreatedAtTrait;
  use UpdatedAtTrait;

  /**
   * @ORM\ManyToOne(targetEntity="MailPoet\Entities\NewsletterEntity")
   * @var NewsletterEntity|null
   */
  private $newsletter;

  /**
   * @ORM\Column(type="string")
   * @Assert\NotBlank()
   * @var string
   */
  private $name;

  /**
   * @ORM\Column(type="string")
   * @var string
   */
  private $categories = '[]';

  /**
   * @ORM\Column(type="string")
   * @var string
   */
  private $description = '';

  /**
   * @ORM\Column(type="json")
   * @Assert\NotBlank()
   * @var array|null
   */
  private $body;

  /**
   * @ORM\Column(type="string", nullable=true)
   * @var string|null
   */
  private $thumbnail;

  /**
   * @ORM\Column(type="boolean")
   * @var bool
   */
  private $readonly = false;

  /**
   * @return NewsletterEntity|null
   */
  public function getNewsletter() {
    return $this->newsletter;
  }

  /**
   * @param NewsletterEntity|null $newsletter
   */
  public function setNewsletter($newsletter) {
    $this->newsletter = $newsletter;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name) {
    $this->name = $name;
  }

  public function getCategories(): string {
    return $this->categories;
  }

  public function setCategories(string $categories) {
    $this->categories = $categories;
  }

  public function getDescription(): string {
    return $this->description;
  }

  public function setDescription(string $description) {
    $this->description = $description;
  }

  /**
   * @return array|null
   */
  public function getBody() {
    return $this->body;
  }

  /**
   * @param array|null $body
   */
  public function setBody($body) {
    $this->body = $body;
  }

  /**
   * @return string|null
   */
  public function getThumbnail() {
    return $this->thumbnail;
  }

  /**
   * @param string|null $thumbnail
   */
  public function setThumbnail($thumbnail) {
    $this->thumbnail = $thumbnail;
  }

  public function getReadonly(): bool {
    return $this->readonly;
  }

  public function setReadonly(bool $readonly) {
    $this->readonly = $readonly;
  }
}