<?php namespace Huddle\Zendesk\Services;

use Config, InvalidArgumentException, BadMethodCallException;
use Zendesk\API\HttpClient;

class ZendeskService {

    /**
     * Get auth parameters from config, fail if any are missing.
     * Instantiate API client and set auth token.
     *
     * @throws Exception
     */
    public function __construct($subdomain, $username, $token) {
        $this->subdomain = $subdomain;
        $this->username = $username;
        $this->token = $token;
        if(!$this->subdomain || !$this->username || !$this->token) {
            throw new InvalidArgumentException('Please set subdomain, username, and token parameters');
        }
        $this->client = new HttpClient($this->subdomain, $this->username);
        $this->client->setAuth('basic', ['username' => $this->username, 'token' => $this->token]);
    }

    /**
     * Pass any method calls onto $this->client
     *
     * @return mixed
     */
    public function __call($method, $args) {
        if(is_callable([$this->client,$method])) {
            return call_user_func_array([$this->client,$method],$args);
        } else {
            throw new BadMethodCallException("Method $method does not exist");
        }
    }

    /**
     * Pass any property calls onto $this->client
     *
     * @return mixed
     */
    public function __get($property) {
        if(property_exists($this->client,$property)) {
            return $this->client->{$property};
        } else {
            throw new BadMethodCallException("Property $property does not exist");
        }
    }

}
