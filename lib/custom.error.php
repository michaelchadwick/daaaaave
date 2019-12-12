<?php
class CustomError {
  private $body = null;
  private $customType = 'server';
  private $error = true;
  private $message = '';
  private $status = 200;
  private $statusText = 'OK';

  public function __construct($values = null)
  {
    if ($values) {
      $this->body ?: $values['body'];
      $this->customType ?: $values['customType'];
      $this->error ?: $values['error'];
      $this->message ?: $values['message'];
      $this->status ?: $values['status'];
      $this->statusText ?: $values['statusText'];
    }
  }
}
?>