<?php namespace Huddle\Zendesk\Services;

use Config, InvalidArgumentException, BadMethodCallException;
use Zendesk\API\HttpClient;
use Illuminate\Support\Facades\Log;

class ZendeskService {

  /**
   * Get auth parameters from config, fail if any are missing.
   * Instantiate API client and set auth token.
   *
   * @throws Exception
   */
  public function __construct($subdomain, $username, $token) {
    $this->driver = config('zendesk-laravel.driver', 'api');

    if ($this->driver === 'api') {
      $this->subdomain = $subdomain;
      $this->username = $username;
      $this->token = $token;
      if(!$this->subdomain || !$this->username || !$this->token) {
        throw new InvalidArgumentException('Please set subdomain, username, and token parameters');
      }
      $this->client = new HttpClient($this->subdomain, $this->username);
      $this->client->setAuth('basic', ['username' => $this->username, 'token' => $this->token]);
    }
  }

  /**
   * Pass any method calls onto $this->client
   *
   * @return mixed
   */
  public function __call($method, $args) {
    if ($this->driver === 'api') {
      if(is_callable([$this->client,$method])) {
        return call_user_func_array([$this->client,$method],$args);
      } else {
        throw new BadMethodCallException("Method $method does not exist");
      }
    } elseif ($this->driver === 'log') {
      Log::debug('Called Huddle Zendesk facade method: '.$method.' with:', $args);

      return $this;
    }
  }

  /**
   * Pass any property calls onto $this->client
   *
   * @return mixed
   */
  public function __get($property) {
    if ($this->driver === 'api') {
      if(property_exists($this->client,$property)) {
        return $this->client->{$property};
      } else {
        throw new BadMethodCallException("Property $property does not exist");
      }
    } else {
      return null;
    }
  }

}
