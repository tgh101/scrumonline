<?php

/*
 * Jira controller class to handle all Jira operations
 */
class JiraController extends ControllerBase
{
    public function getIssues()
    {
        include(dirname(__FILE__) . '/../config.php');
        $jiraConfig= $jiraConfiguration;
        //$parameters = array_replace_recursive((array) $defaultparams, $_POST);
        if ($_POST['project']=="japco"){
            $jiraConfig= $jiraConfiguration1;
        }
        $parameters['base_url'] = $_POST['base_url'] ? $_POST['base_url'] : $jiraConfig['base_url'];
        $parameters['jql'] = $_POST['jql'] ? $_POST['jql'] : $jiraConfig['jql'];
        $parameters['username'] = $_POST['username'] ? $_POST['username'] : $jiraConfig['username'];
        $parameters['password'] = $_POST['password'] ? $_POST['password'] : $jiraConfig['password'];

        $jiraUrl = $parameters['base_url'] . '/rest/api/2/search?maxResults=1000&';

        if ($parameters['jql']) {
            if (substr_count(strtolower($parameters['jql']), "order by") == 0 && substr_count(strtolower($parameters['jql']), "order%20by") == 0) {
                $parameters['jql'] .= ' order by priority asc';
            }
            $jql = array('jql' => $parameters['jql']);
            $jiraUrl .= http_build_query($jql);
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
