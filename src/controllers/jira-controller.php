<?php

/*
 * Jira controller class to handle all Jira operations
 */
class JiraController extends ControllerBase
{
    public function getIssues()
    {
        include(dirname(__FILE__) . '/../config.php');
        
        //$parameters = array_replace_recursive((array) $defaultparams, $_POST);
        $parameters['base_url'] = $_POST['base_url'] ? $_POST['base_url'] : $jiraConfiguration['base_url'];
        $parameters['jql'] = $_POST['jql'] ? $_POST['jql'] : $jiraConfiguration['jql'];
        $parameters['username'] = $_POST['username'] ? $_POST['username'] : $jiraConfiguration['username'];
        $parameters['password'] = $_POST['password'] ? $_POST['password'] : $jiraConfiguration['password'];

        $jiraUrl = $parameters['base_url'] . '/rest/api/2/search?';
        if ($parameters['jql']) {
            $jiraUrl .= 'jql=' . $parameters['jql'];
        }

        if (substr_count(strtolower($parameters['jql']), "order by") == 0 && substr_count(strtolower($parameters['jql']), "order%20by") == 0) {
            $jiraUrl .= ' order by priority';
        }

        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $jiraUrl, [
            'auth' => [$parameters['username'], $parameters['password']]
        ]);
        $response = json_decode($res->getBody()->getContents(), true);

        // Add the base URL used for the API request to the response
        $response['base_url'] = $parameters['base_url'];
        
        return $response;
    }
}

return new JiraController($entityManager);
