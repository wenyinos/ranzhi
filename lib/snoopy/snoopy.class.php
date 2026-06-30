<?php
/**
 * Snoopy compatibility wrapper using Guzzle HTTP client.
 * Provides the same fetch()/submit() interface as the old Snoopy class.
 */
class Snoopy
{
    public $results = '';

    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'timeout'         => 30,
            'verify'          => false,
            'http_errors'     => false,
            'allow_redirects' => true,
        ]);
    }

    /**
     * Fetch a URL via GET.
     *
     * @param  string $url
     * @return bool
     */
    public function fetch($url)
    {
        try
        {
            $response = $this->client->get($url);
            $this->results = (string) $response->getBody();
            return true;
        }
        catch(\Exception $e)
        {
            $this->results = '';
            return false;
        }
    }

    /**
     * Submit a POST request.
     *
     * @param  string $url
     * @param  array  $vars
     * @return bool
     */
    public function submit($url, $vars = array())
    {
        try
        {
            $response = $this->client->post($url, ['form_params' => $vars]);
            $this->results = (string) $response->getBody();
            return true;
        }
        catch(\Exception $e)
        {
            $this->results = '';
            return false;
        }
    }
}
