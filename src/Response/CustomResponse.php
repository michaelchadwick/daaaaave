<?php
class CustomResponse
{
  var $body;
  var $contentType;
  var $customType;
  var $error;
  var $message;
  var $status;
  var $statusText;

  public function __construct($values = null)
  {
    if ($values) {
      $this->setBody(
        isset($values['body']) ?
          $values['body'] :
          null
      );
      $this->setContentType(
        isset($values['contentType']) ?
          $values['contentType'] :
          'json'
      );
      $this->setCustomType(
        isset($values['customType']) ?
          $values['customType'] :
          'server'
      );
      $this->setError(
        isset($values['error']) ?
          $values['error'] :
          true
      );
      $this->setMessage(
        isset($values['message']) ?
          $values['message'] :
          ''
      );
      $this->setStatus(
        isset($values['status']) ?
          $values['status'] :
          200
      );
      $this->setStatusText(
        isset($values['statusText']) ?
          $values['statusText'] :
          'OK'
      );
    }

    return $this;
  }

  public function getBody()
  {
    return $this->body;
  }
  public function setBody($body)
  {
    $this->body = $body;
  }

  public function getContentType()
  {
    return $this->contentType;
  }
  public function setContentType($contentType)
  {
    $this->customType = $contentType;
  }

  public function getCustomType()
  {
    return $this->customType;
  }
  public function setCustomType($customType)
  {
    $this->customType = $customType;
  }

  public function getError()
  {
    return $this->error;
  }
  public function setError($error)
  {
    $this->error = $error;
  }

  public function getMessage()
  {
    return $this->message;
  }
  public function setMessage($message)
  {
    $this->message = $message;
  }

  public function getStatus()
  {
    return $this->status;
  }
  public function setStatus($status)
  {
    $this->status = $status;
  }

  public function getStatusText()
  {
    return $this->statusText;
  }
  public function setStatusText($statusText)
  {
    $this->statusText = $statusText;
  }
}
