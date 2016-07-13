<?php
namespace DAG\JIRA\BuildsTable;

/**
 * Class Client
 */
final class Client
{
    /** @var string */
    private $jiraUser;

    /** @var string */
    private $jiraPassword;

    /** @var string */
    private $jiraURL;

    /**
     * Client constructor.
     *
     * @param string $jiraUser
     * @param string $jiraPassword
     * @param string $jiraURL
     */
    public function __construct($jiraUser, $jiraPassword, $jiraURL)
    {
        $this->jiraUser = $jiraUser;
        $this->jiraPassword = $jiraPassword;
        $this->jiraURL = $jiraURL;
    }

    /**
     * @param integer $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getPage($id)
    {
        $url = $this->jiraURL.'/wiki/rest/api/content/'.$id.'?expand=body.view,version,ancestors';
        $auth = base64_encode($this->jiraUser.':'.$this->jiraPassword);
        $header = [
            "Authorization: Basic $auth",
            'Content-type: application/json',
        ];
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => $header,
            ],
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if ($http_response_header[0] != 'HTTP/1.1 200 OK') {
            throw new \Exception(sprintf('Could not get page. HTTP response : "%s"', $http_response_header[0]));
        }

        return json_decode($response, true);
    }

    public function sendPage($id, array $content)
    {
        $url = $this->jiraURL.'/wiki/rest/api/content/'.$id;
        $postdata = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $auth = base64_encode($this->jiraUser.':'.$this->jiraPassword);
        $header = [
            "Authorization: Basic $auth",
            'Content-type: application/json',
        ];
        $opts = [
            'http' => [
                'method' => 'PUT',
                'header' => $header,
                'content' => $postdata,
            ],
        ];
        $context = stream_context_create($opts);
        $content = @file_get_contents($url, false, $context);
        if ($http_response_header[0] != 'HTTP/1.1 200 OK') {
            throw new \Exception(
                sprintf(
                    'Could not post page. HTTP response : "%s" and content : "%s"',
                    $http_response_header[0],
                    $content
                )
            );
        }
    }

    public function getAttachment($pageId, $attachmentName)
    {
        $url = $this->jiraURL.'/wiki/rest/api/content/'.$pageId.'/child/attachment?filename='.$attachmentName.'&expand=version,container';
        $auth = base64_encode($this->jiraUser.':'.$this->jiraPassword);
        $header = [
            "Authorization: Basic $auth",
            'Content-type: application/json',
        ];
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => $header,
            ],
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if ($http_response_header[0] != 'HTTP/1.1 200 OK') {
            throw new \Exception(sprintf('Could not get page. HTTP response : "%s"', $http_response_header[0]));
        }

        return json_decode($response, true);
    }

    public function downloadAttachmentContent($pageId, $attachmentName)
    {
        $url = $this->jiraURL.'/wiki/download/attachments/'.$pageId.'/'.$attachmentName.'?api=v2';
        $auth = base64_encode($this->jiraUser.':'.$this->jiraPassword);
        $header = [
            "Authorization: Basic $auth",
            'Content-type: application/json',
        ];
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => $header,
            ],
        ];
        $context = stream_context_create($opts);
        $response = @file_get_contents($url, false, $context);

        if ($http_response_header[0] != 'HTTP/1.1 200 OK') {
            throw new \Exception(sprintf('Could not get page. HTTP response : "%s"', $http_response_header[0]));
        }

        return json_decode($response, true);
    }

    public function uploadAttachment($pageId, $attachmentName, $content, $attachmentId = null)
    {
        if ($attachmentId) {
            $url = $this->jiraURL.'/wiki/rest/api/content/'.$pageId.'/child/attachment/'.$attachmentId.'/data';
        } else {
            $url = $this->jiraURL.'/wiki/rest/api/content/'.$pageId.'/child/attachment';
        }

        $client = new \GuzzleHttp\Client(
            [
                // Base URI is used with relative requests
                'base_uri' => $this->jiraURL,
                // You can set any number of default request options.
                'timeout' => 10.0,
            ]
        );

        $client->request(
            'POST',
            $url,
            [
                'auth' => [$this->jiraUser, $this->jiraPassword],
                'headers' => [
                    'X-Atlassian-Token' => 'no-check',
                ],
                'multipart' => [
                    [
                        'name' => 'file',
                        'filename' => $attachmentName,
                        'contents' => $content,
                    ]
                ],
            ]
        );
    }
}
